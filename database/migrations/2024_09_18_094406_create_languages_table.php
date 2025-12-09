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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->string('key', 5)->comment('Language unique identifier');
            $table->tinyInteger('sort_order', 3)->comment('Sort Ordering');
            $table->tinyInteger('status', 1)->comment('0: Deactivate, 1: Activate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
