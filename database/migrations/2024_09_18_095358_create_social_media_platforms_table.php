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
        Schema::create('social_media_platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->tinyInteger('status', 1)->comment('0: Disabled, 1: Enabled');
            $table->timestamps();

            $table->index('status', 'status_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_media_platforms', function (Blueprint $table) {
            $table->dropIndex(['status_IDX']);
        });

        Schema::dropIfExists('social_media_platforms');
    }
};
