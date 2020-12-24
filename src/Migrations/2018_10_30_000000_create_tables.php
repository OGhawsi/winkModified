<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wink_tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('locale')->default('en');
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('wink_posts_tags', function (Blueprint $table) {
            $table->uuid('post_id');
            $table->uuid('tag_id');

            $table->unique(['post_id', 'tag_id']);
        });

        Schema::create('wink_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->text('bio');
            $table->timestamps();

        });

        Schema::create('wink_posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('excerpt');
            $table->string('locale')->default('en');
            $table->boolean('featured')->default(false);
            $table->text('body');
            $table->boolean('published')->default(false);
            $table->dateTime('publish_date')->default('2018-10-10 00:00:00');
            $table->string('featured_image')->nullable();
            $table->string('featured_image_caption');
            $table->uuid('author_id')->index();
            $table->timestamps();
        });

        Schema::create('wink_authors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->text('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('bio');
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('wink_pages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });

        /**
         * Roles and Abilities
         * 
         */
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::create('abilities', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::create('ability_role', function (Blueprint $table) {
            $table->unique(['role_id', 'ability_id']);

            $table->uuid('role_id');
            $table->uuid('ability_id');
            $table->timestamps();

            // $table->foreign('role_id')
            //     ->references('id')
            //     ->on('roles')
            //     ->onDelete('cascade');

            // $table->foreign('ability_id')
            //     ->references('id')
            //     ->on('abilities')
            //     ->onDelete('cascade');
        });

        Schema::create('wink_author_role', function (Blueprint $table) {
            $table->unique(['author_id', 'role_id']);

            $table->uuid('author_id');
            $table->uuid('role_id');
            $table->timestamps();

            // $table->foreign('author_id')
            //     ->references('id')
            //     ->on('wink_authors')
            //     ->onDelete('cascade');

            // $table->foreign('role_id')
            //     ->references('id')
            //     ->on('roles')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wink_tags');
        Schema::dropIfExists('wink_posts_tags');
        Schema::dropIfExists('wink_authors');
        Schema::dropIfExists('wink_posts');
        Schema::dropIfExists('wink_pages');

        Schema::dropIfExists('roles');
        Schema::dropIfExists('abilities');
        Schema::dropIfExists('wink_author_role');
    }
}
