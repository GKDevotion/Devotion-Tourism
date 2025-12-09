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
        Schema::create('person_meetings', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('person_id')->comment('reference for the person table');
            $table->integer('communication_type_id')->comment('reference for the communication type table');
            $table->string('title', 100);
            $table->date('date');
            $table->text('description');
            $table->string('follow_up_details');
            $table->datetimes('follow_up_date');
            $table->tinyInteger('status')->comment('0: Disabled, 1: Enabled');
            $table->timestamps();

            $table->index('person_id', 'person_IDX');
            $table->index('status', 'status_IDX');
            $table->index('admin_id', 'admin_IDX');
            $table->index('communication_type_id', 'communication_type_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('person_meetings', function (Blueprint $table) {
            $table->dropIndex(['person_IDX', 'communication_type_IDX', 'status_IDX', 'admin_IDX']);
        });

        Schema::dropIfExists('person_meetings');
    }
};
