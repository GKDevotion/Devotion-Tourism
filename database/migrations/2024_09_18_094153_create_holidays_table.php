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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('title', 80);
            $table->string('slug', 80);
            $table->enum('type', ['Bank', 'Public', 'Optional'])->comment('Holiday Types');
            $table->date('date');
            $table->string('card_image', 150)->comment('Card image');
            $table->string('banner_image', 150)->comment('Banner image');
            $table->text('short_description');
            $table->tinyInteger('status', 1)->comment('0: Disabled, 1: Enabled	');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
