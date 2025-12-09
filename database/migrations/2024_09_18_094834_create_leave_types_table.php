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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('purpose', 100);
            $table->string('duration', 150);
            $table->string('requirement', 150);
            $table->tinyInteger('allow_days', 2);
            $table->tinyInteger('status', 1)->comment('1: Active, 0: De Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
