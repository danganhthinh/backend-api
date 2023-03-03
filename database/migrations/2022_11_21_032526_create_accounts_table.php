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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('student_code')->nullable();
            $table->string('password');
            $table->string('display_password');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('grade_id')->nullable()->constrained('grades')->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade');
            // $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('cascade');
            $table->string('email')->nullable()->unique();
            $table->string('phone_number')->nullable();
            $table->date('birthday')->nullable();
            $table->string('level_collection')->default('[]');
            $table->boolean('check_first_login')->default(0)->comment("Check to change password the first time you log in (0 has not changed, 1 has changed)");
            $table->integer('number_open_app')->default(0)->comment("Number of times opening the app");
            $table->bigInteger('usage_time')->nullable();
            $table->timestamp('effective_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->boolean('status')->default(1)->comment('0 inactive, 1 active');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('accounts');
    }
};
