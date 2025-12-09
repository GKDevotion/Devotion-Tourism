<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_performance', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('user_id')->comment('reference for the user table');
            $table->integer('person_id')->comment('reference for the person table');
            $table->string('review_period', 30);
            $table->string('head_of_dept', 50);
            $table->integer('demonstrated');
            $table->integer('demonstrated_score');
            $table->string('demonstrated_comments', 100);
            $table->integer('timeliness');
            $table->integer('timeliness_score');
            $table->string('timeliness_score_comments', 300);
            $table->integer('impact');
            $table->integer('impact_score');
            $table->string('impact_score_comments', 300);
            $table->integer('overall');
            $table->integer('overall_score');
            $table->string('overall_score_comments', 300);
            $table->integer('beyond_duty');
            $table->string('beyond_duty_score_comments', 300);
            $table->integer('interpersonal');
            $table->integer('interpersonal_score');
            $table->string('interpersonal_score_comments', 300);
            $table->integer('attendance');
            $table->integer('attendance_score');
            $table->string('attendance_score_comments', 300);
            $table->integer('communication');
            $table->integer('communication_score');
            $table->string('communication_score_comments', 300);
            $table->integer('contributing');
            $table->integer('contributing_score');
            $table->string('contributing_score_comments', 300);
            $table->string('assessment_b_total_score', 300);
            $table->text('employee_comments');
            $table->text('dev_plan_item');
            $table->text('key_goals_item');
            $table->tinyInteger('status')->comment('0: De-Active, 1: Active');
            $table->timestamps();

            $table->index('person_id', 'person_IDX');
            $table->index(['admin_id', 'user_id'], 'admin_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_performance', function (Blueprint $table) {
            $table->dropIndex(['person_IDX', 'admin_IDX']);
        });

        Schema::dropIfExists('employee_performance');
    }
};
