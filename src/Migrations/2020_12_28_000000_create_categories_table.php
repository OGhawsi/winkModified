<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wink_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('locale')->default('en');
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('wink_categories_posts', function (Blueprint $table) {
            $table->uuid('category_id');
            $table->uuid('post_id');

            $table->unique(['category_id','post_id']);
        });
    }

      

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wink_categories');
        Schema::dropIfExists('wink_categories_posts');
    }
}
