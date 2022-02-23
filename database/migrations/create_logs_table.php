<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('user_logs')){
            Schema::create('user_logs', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->uuid('uuid')->unique();
                $table->bigInteger('user_id')->unsigned()->nullable();
                $table->dateTime('login_time')->nullable();
                $table->dateTime('logout_time')->nullable();
                $table->string('ip_address',45)->nullable();
                $table->enum('remember_me', ['0', '1'])->nullable();
                $table->string('browser_name',255)->nullable();
                $table->string('version',255)->nullable();
                $table->string('platform',255)->nullable();
                $table->enum('browser_device', ['AOL', 'Facebook','Mobile','Robot','Tablet','unknown'])->nullable();
                $table->enum('isActive', ['0', '1'])->default('1');
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
            Schema::dropIfExists('user_logs');
        }
    }
}
