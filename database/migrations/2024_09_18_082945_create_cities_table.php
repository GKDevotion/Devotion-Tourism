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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->integer('continent_id')->comment('reference for the continent table');
            $table->integer('country_id')->comment('reference for the country table');
            $table->integer('state_id')->comment('reference for the state table');
            $table->string('name', 30);
            $table->string('slug', 30);
            $table->decimal('latitude', 10, 3);
            $table->decimal('longitude', 10, 3);
            $table->tinyInteger('status')->comment('0: Disabled, 1: Enable');
            $table->timestamps();

            $table->index( ['continent_id', 'country_id', 'state_id'], 'continent_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropIndex(['continent_IDX']);
        });

        Schema::dropIfExists('cities');
    }
};
