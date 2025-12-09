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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id');
            $table->string('name', 30);
            $table->string('slug');
            $table->text('description');
            $table->tinyInteger('status', 1)->comment('0: Disabled, 1: Enabled');
            $table->tinyInteger('sort_order', 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
