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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('user_id')->comment('reference for the user table');
            $table->integer('perrson_id')->comment('reference for the person table');
            $table->integer('leave_type_id')->comment('reference for the leave type table');
            $table->date('from_date');
            $table->date('end_date');
            $table->date('apply_date');
            $table->date('approve_date');
            $table->tinyInteger('total_day', 3);
            $table->tinyInteger('approve_date', 3);
            $table->text('reason');
            $table->string('approved_by', 50);
            $table->tinyInteger('status', 1)->comment('1: Active, 0: De Active');
            $table->timestamps();

            $table->index('person_id', 'person_IDX');
            $table->index( ['user_id', 'admin_id'], 'user_IDX');
            $table->index( 'leave_type_id', 'leave_type_IDX');
            $table->index('status', 'status_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropIndex(['person_IDX', 'leave_type_IDX', 'status_IDX', 'user_IDX']);
        });

        Schema::dropIfExists('leaves');
    }
};
