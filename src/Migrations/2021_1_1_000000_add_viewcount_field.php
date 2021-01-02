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
            $table->bigInteger('viewcount');
            $table->bigInteger('like');
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
        });
    }
}
