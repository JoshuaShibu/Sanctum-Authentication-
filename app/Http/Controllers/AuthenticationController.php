<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\MiscHelpers;
use Illuminate\Support\Facades\Hash;


class AuthenticationController extends Controller
{
    protected $miscHelper;
    protected $user;

    public function __construct()
    {
        $this->miscHelper = new MiscHelpers();
        $this->user = new User();
    }

    // this method adds new users
    public function createAccount(Request $request)
    {
        $attr = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ],
        [
            'required' => 'This field is required.',
            'first_name' => 'first name is required',
            'last_name' => 'last name is required',
            'email.required' => 'email is required',
            'email.unique' => 'email already exists',
            'password.required' => 'password is required',
        ]);

        $user = User::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'password' => bcrypt($request->get('password')),
            'email' => $request->get('email'),
        ]);

        return $this->miscHelper->returnPayload([
            'status' => 'success',
            'token_type' => 'Bearer',
            'token' => $user->createToken('tokens')->plainTextToken
        ]);
    }

    //use this method to signin users
    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'This field is required.',
            'email.email' => 'Please enter a valid email.',
            'password.required' => 'This field is required.',
        ]);

        // check email
        $user = User::where('email',  $attr['email'])->first();

        //check password
        if (empty($user)|| !Hash::check($attr['password'], $user->password)){
            return $this->miscHelper->returnValidationError('No User found or your credentials are incorrect.', 422);
        }

        return $this->miscHelper->returnPayload([
            'user' => $user,
            'token_type' => 'Bearer',
            'token' => $user->createToken('tokens')->plainTextToken
        ]);
    }

    // this method signs out users by removing tokens
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}
