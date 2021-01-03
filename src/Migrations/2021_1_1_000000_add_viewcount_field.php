<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewcountField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wink_posts', function (Blueprint $table) {
            $table->bigInteger('viewcount')->default(0);
            $table->bigInteger('like')->default(0);
            $table->uuid('category_id')->index()->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wink_posts', function (Blueprint $table) {
            $table->bigInteger('viewcount');
            $table->bigInteger('like');
            $table->uuid('category_id')->index()->nullable();
        });
    }
}
