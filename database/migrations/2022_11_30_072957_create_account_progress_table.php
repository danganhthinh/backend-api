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
        Schema::create('account_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('grade_id')->nullable()->constrained('grades')->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('cascade');
            $table->string('subjects_id')->nullable();
            $table->foreignId('video_id')->nullable()->constrained('videos')->onDelete('cascade');
            $table->integer('subject_level')->nullable();
            $table->text('subjects_level')->nullable();
            $table->text('questions_id')->nullable();
            $table->text('wrong_questions')->nullable();
            $table->text('correct_questions')->nullable();
            $table->text('correct_video_questions')->nullable();
            $table->integer('type')->comment('1 Training, 2 Video, 3 Training Random');
            $table->boolean('status')->default(0)->comment('0 doing, 1 done');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('account_progress');
    }
};
