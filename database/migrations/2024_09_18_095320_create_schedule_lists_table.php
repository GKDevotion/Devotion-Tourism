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
        Schema::create('schedule_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->string('title', 30);
            $table->text('description');
            $table->datetimes('start_date');
            $table->datetimes('end_date');
            $table->tinyInteger('type', 1)->comment('1: Employee, 2: Customer, 3: Client');
            $table->timestamps();

            $table->index('admin_id', 'admin_IDX');
            $table->index('type', 'type_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_lists', function (Blueprint $table) {
            $table->dropIndex(['type_IDX', 'admin_IDX']);
        });

        Schema::dropIfExists('schedule_lists');
    }
};
