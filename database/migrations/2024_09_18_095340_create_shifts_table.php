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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->smallInteger('max_absence_time');
            $table->smallInteger('late_tolerance');
            $table->text('description');
            $table->string('common', 50);
            $table->string('pros', 50);
            $table->string('cons', 50);
            $table->tinyInteger('status', 1)->comment('1: Enable, 0: Disabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
