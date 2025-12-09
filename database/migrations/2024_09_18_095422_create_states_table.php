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
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->integer('continent_id')->comment('reference for the continent table');
            $table->integer('country_id')->comment('reference for the country table');
            $table->string('name', 50);
            $table->string('slug', 50);
            $table->string('fips_code', 10);
            $table->string('iso2', 3);
            $table->decimal('latitude', 10, 3);
            $table->decimal('longitude', 10, 3);
            $table->tinyInteger('status', 1)->comment('0: Disabled, 1: Enabled');
            $table->timestamps();

            $table->index( ['continent_id', 'country_id'], 'continent_IDX');
            $table->index('status', 'status_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex(['continent_IDX', 'status_IDX']);
        });

        Schema::dropIfExists('states');
    }
};
