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
        Schema::create('social_medias', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('person_id')->comment('reference for the person table');
            $table->string( 'facebook', 30 );
            $table->string( 'instagram', 30 );
            $table->string( 'twitter', 30 );
            $table->string( 'whats_app', 30 );
            $table->string( 'youtube', 30 );
            $table->string( 'linked_in', 30 );
            $table->string( 'telegram', 30 );
            $table->string( 'pinterest', 30 );
            $table->timestamps();

            $table->index('admin_id', 'admin_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex(['admin_IDX']);
        });

        Schema::dropIfExists('social_medias');
    }
};
