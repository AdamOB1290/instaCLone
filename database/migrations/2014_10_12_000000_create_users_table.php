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
            $table->id();
            $table->string('name');
            $table->string('gender')->nullable();
            $table->string('birthdate')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('role')->default('user');
            $table->json('followers')->nullable();
            $table->json('followed')->nullable();
            $table->string('pfp')->nullable()->default('uploads/default_pic.jpg');
            $table->string('username')->nullable();
            $table->string('password');
            $table->string('website')->nullable();
            $table->text('bio')->nullable();
            $table->json('favorites')->nullable();
            $table->json('liked')->default('{"posts":[],"comments":[]}');
            $table->string('notification')->nullable();
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
