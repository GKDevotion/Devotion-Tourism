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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('user_id')->comment('reference for the user table');
            $table->integer('person_id')->comment('reference for the person table');
            $table->date('date');
            $table->text('time_in_out')->comment('store time in out into jsonm format');
            $table->time('time')->comment('rest between in out time');
            $table->tinyInteger('status')->comment('0: Reject, 1: Approve');
            $table->timestamps();

            $table->index('person_id', 'person_IDX');
            $table->index('date', 'date_IDX');
            $table->index( ['admin_id', 'user_id'], 'admin_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['person_IDX', 'date_IDX', 'admin_IDX']);
        });

        Schema::dropIfExists('attendances');
    }
};
