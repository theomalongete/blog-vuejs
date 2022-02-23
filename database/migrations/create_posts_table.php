<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('posts')){
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->uuid('uuid')->unique();
                $table->string('post_title',255)->nullable();
                $table->text('post_content')->nullable();
                $table->bigInteger('user_id')->nullable();
                $table->enum('post_status', ['Active','Deleted'])->default("Active"); 
                $table->enum('isActive', ['0', '1'])->default('1');
                $table->softDeletes();
            });
        }

        if(!Schema::hasTable('post_has_comments')){
            Schema::create('post_has_comments', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->uuid('uuid')->unique();
                $table->text('comment_message')->nullable();
                $table->bigInteger('post_id')->nullable();
                $table->enum('comment_status', ['Active','Deleted'])->default("Active"); 
                $table->enum('isActive', ['0', '1'])->default('1');
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
            Schema::dropIfExists('posts');
            Schema::dropIfExists('post_has_comments');
        }
    }
}
