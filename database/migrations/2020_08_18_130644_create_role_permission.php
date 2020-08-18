<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->string('role_name');
            $table->string('role_display_name');
            $table->timestamps();
        });

        Schema::create('permission',function(Blueprint $table){
            $table->id();
            $table->string('permission_name')->nullable();
            $table->string('permission_display_name');
            $table->timestamps();
        });

        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id')->unsigned()->nullable();
            $table->integer('permission_id')->unsigned()->nullable();
            $table->integer('role_display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('user_role',function(Blueprint $table){
           $table->id();
           $table->integer('user_id');
           $table->integer('role_id');
           $table->timestamps();
        });

        /**
         * Practically, this user_permission will get all the permission from the associated permission that it have under certain role
         */
        Schema::table('user_permission',function(Blueprint $table){
            $table->id();
            $table->integer('user_id');
            $table->integer('permission_id');
            $table->timestamps();
        });




        /**
         *  Company role
         */
        Schema::create('company_role',function(Blueprint $table){
            $table->id();
            $table->string('role_name');
            $table->string('role_display_name');
            $table->timestamps();
        });

        Schema::create('company_role_assign',function(Blueprint $table){
           $table->id();
           $table->integer('company_id')->unsigned()->nullable();
           $table->integer('company_role_id')->unsigned()->nullable();
           $table->timestamps();
        });


        /**
         * This is allowed company role can have multiple role in their organization in a more structured way
         */
        Schema::create('company_role_user_role',function(Blueprint $table){
            $table->id();
            $table->string('company_role_id')->nullable(); // this is from the company role table
            $table->string('role_id')->nullable(); // this is from the role table
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
        Schema::dropIfExists('role');
    }
}
