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
        Schema::create('job_roles', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->comment('Reference for the any parent job available');
            $table->string('name', 50);
            $table->string('slug', 50);
            $table->tinyInteger('status', 1)->comment('0: Disabled, 1: Enabled');
            $table->tinyInteger('sort_order')->comment('sort ordering');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_roles');
    }
};
