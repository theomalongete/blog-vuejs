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
        if(!Schema::hasTable('users')){
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->uuid('uuid')->unique();
                $table->string('user_first_name',255)->nullable();
                $table->string('user_surname',255)->nullable();
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('timezone',255)->default('Africa/Johannesburg');
                $table->text('api_token')->unique()->nullable(); 
                $table->enum('user_status', ['Active','Suspended','Deleted'])->default("Active"); 
                $table->enum('isActive', ['0', '1'])->default('1');
                $table->enum('logged', ['0', '1'])->default('0');
                $table->softDeletes();
            
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (app()->environment('local','development')){
            Schema::dropIfExists('users');
        }
    }
}
