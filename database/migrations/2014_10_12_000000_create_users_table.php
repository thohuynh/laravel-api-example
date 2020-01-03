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
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('user_name')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->boolean('sex');
            $table->date('birthday');
            $table->string('street_address_now')->nullable();
            $table->string('number_address_now')->nullable();
            $table->unsignedInteger('district_id_now')->nullable();
            $table->unsignedInteger('city_id_now')->nullable();
            $table->string('street_address_native_village')->nullable();
            $table->string('number_address_native_village')->nullable();
            $table->unsignedInteger('district_id_native_village')->nullable();
            $table->unsignedInteger('city_id_native_village')->nullable();
            $table->string('work_at')->nullable();
            $table->string('work_now')->nullable();
            $table->unsignedInteger('voice_id')->nullable();
            $table->unsignedInteger('level_id')->nullable();
            $table->text('teaching_classes')->nullable();
            $table->text('teaching_subjects')->nullable();
            $table->text('teaching_at_districts')->nullable();
            $table->string('identity_card')->nullable();
            $table->string('degree')->nullable();
            $table->string('avatar')->nullable();
            $table->unsignedInteger('role_id')->default(1);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
