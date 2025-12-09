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
        Schema::create('human_health_conditions', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->comment('reference for parent');
            $table->string('name', 50);
            $table->string('slug', 50);
            $table->tinyInteger('sort_order', 3)->comment('sort ordering');
            $table->tinyInteger('status', 1)->comment('0: Disabled, 1: Enabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('human_health_conditions');
    }
};
