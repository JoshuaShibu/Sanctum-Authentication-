<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
           $table->increments('id');

            $table->string('first_name', 255)
                ->charset('utf8')->collation('utf8_unicode_ci')
                ->nullable(false);

            $table->string('last_name', 255)
                ->charset('utf8')->collation('utf8_unicode_ci')
                ->nullable(false);

            $table->string('email', 255)
                ->charset('utf8')->collation('utf8_unicode_ci')
                ->unique()
                ->nullable(false);

            $table->string('password', 255)
                ->charset('utf8')->collation('utf8_unicode_ci')
                ->nullable(false);

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
