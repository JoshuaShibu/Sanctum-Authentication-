<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class MiscHelpers
{

    public function returnError(
        $message = 'Internal Server Error',
        $exception = 'Encountered an unexpected internal server error. Please try again later.',
        $status = 500
    ) {
        $errors = [
            'message' => $message,
            'exception' => $exception,
        ];

        return response($errors, $status)->withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }

    public function returnValidationError($key, $error)
    {
        $errors = [
            'errors' => [
                ('data.' . $key) => [$error],
            ],
            'message' => 'The given data was invalid.',
        ];

        return response($errors, 422)->withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Creates a success payload and returns.
     *
     * @param  string  $title
     * @param  string  $detail
     * @param  int  $status  An HTTP status code
     * @return json
     */
    public function returnSuccess(
        $title = 'Success',
        $message = 'The request completed successfully',
        $status = 200
    ) {
        $payload = [
            'title' => $title,
            'message' => $message,
        ];

        return response($payload, $status)->withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Returns 'data' payload with proper headers.
     *
     * @param  array  $data  The PHP array which needs to be returned as JSON
     * @return json
     */
    public function returnPayload($data = [], $status = 200)
    {
        return response(['data' => $data], $status)->withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Returns custom 'data' payload with proper headers.
     * Also supports adding additional data.
     *
     * @param  array  $data  The PHP array which needs to be returned as JSON
     * @param  array  $additionalData  The PHP array which we want to add with $data array
     * @param  int  $status  Status code of response
     * @return json
     */
    public function returnCustomPayload($data = [], $additionalData = null, $status = 200)
    {
        if (! is_array($additionalData) && empty($additionalData)) {
            return $this->returnPayload($data);
        }

        $response['data'] = $data;

        foreach ($additionalData as $key => $value) {
            $response[$key] = $value;
        }

        return response($response, $status)->withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }
}
