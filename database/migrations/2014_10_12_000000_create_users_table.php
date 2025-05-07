<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_212102', function (Blueprint $table) {
            $table->id('id_212102');
            $table->string('name_212102');
            $table->string('email_212102')->unique();
            $table->string('telephone_212102');
            $table->string('role_212102');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password_212102');
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_212102');
    }
};