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
        Schema::create('notice_boards', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('user_id')->comment('reference for the user table');
            $table->string('name', 50);
            $table->string('description', 200);
            $table->date('date');
            $table->string('attachament', 100);
            $table->string('notice_by', 50);
            $table->tinyInteger('sort_order', 1)->comment('1: Active, 0: De Active');
            $table->timestamps();

            $table->index('user_id', 'user_IDX');
            $table->index('admin_id', 'admin_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notice_boards', function (Blueprint $table) {
            $table->dropIndex(['user_IDX', 'admin_IDX']);
        });

        Schema::dropIfExists('notice_boards');
    }
};
