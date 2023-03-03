<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->after('status',function($table){
                $table->integer('check_furigana')->default(0);
                $table->text('content_furigana')->nullable();
                $table->text('answer1_furigana')->nullable();
                $table->text('answer2_furigana')->nullable();
                $table->text('answer3_furigana')->nullable();
                $table->text('answer4_furigana')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
