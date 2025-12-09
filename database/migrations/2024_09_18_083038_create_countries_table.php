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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->integer('continent_id')->comment('reference for the continent table');
            $table->string('name', 50);
            $table->string('slug', 50);
            $table->char('iso3', 3);
            $table->char('numeric_code', 3);
            $table->char('iso2');
            $table->string('phone_code', 4);
            $table->string('capital', 50);
            $table->string('currency_name', 10);
            $table->string('currency_symbol', 5);
            $table->string('tld', 10);
            $table->string('latitude', 10);
            $table->string('longitude', 10);
            $table->tinyInteger('status', 1)->comment('0: De-Active, 1: Active');
            $table->timestamps();

            $table->index('continent_id', 'continent_IDX');
            $table->index('status', 'status_IDX');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropIndex(['continent_IDX', 'status_IDX']);
        });
        Schema::dropIfExists('countries');
    }
};
