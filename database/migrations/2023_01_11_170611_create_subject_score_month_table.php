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
        Schema::create('subject_score_month', function (Blueprint $table) {
            $table->id();
            $table->integer('month');
            $table->integer('year');
            $table->foreignId('subject_score_id')->nullable()->constrained('subject_score')->onDelete('cascade');
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('grade_id')->nullable()->constrained('grades')->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('cascade');
            $table->foreignId('level_id')->nullable()->constrained('levels')->onDelete('cascade');
            $table->double('average_score', 10, 2)->default(0);
            $table->integer('number_training')->default(0);
            $table->integer('total_questions')->default(0);
            $table->integer('correct_answer')->default(0);
            $table->text('corrects_id')->nullable();
            $table->integer('correct_answer_video')->default(0);
            $table->integer('number_correct_answers')->default(0);
            $table->integer('number_wrong_answer')->default(0);
            $table->integer('video_number_learning')->default(0);
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
        Schema::dropIfExists('subject_score_month');
    }
};
