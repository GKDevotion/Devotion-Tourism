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
        Schema::create('shift_details', function (Blueprint $table) {
            $table->id();
            $table->integer('shift_id')->comment('reference for the shift table');
            $table->integer('admin_id')->comment('reference for the admin');
            $table->tinyInteger('day');
            $table->tinyInteger('type')->comment('1: WORKDAY, 2: OFFDAY	');
            $table->time('check_in');
            $table->time('check_out');
            $table->timestamps();

            $table->index('admin_id', 'admin_IDX');
            $table->index('type', 'type_IDX');
            $table->index('shift_id', 'shift_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_details', function (Blueprint $table) {
            $table->dropIndex(['shift_IDX', 'type_IDX', 'admin_IDX']);
        });

        Schema::dropIfExists('shift_details');
    }
};
