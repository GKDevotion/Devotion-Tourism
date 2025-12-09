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
        Schema::create('device_records', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('user_id')->comment('reference for the user table');
            $table->string('company', 50)->comment('company name');
            $table->text('configuration');
            $table->decimal('charge', 5, 2);
            $table->string('portal_link', 100);
            $table->string('login_id', 50);
            $table->string('password', 50);
            $table->string('web', 100);
            $table->string('authorise_person', 50);
            $table->timestamps();
            $table->tinyInteger('deleted_at', 1)->comment('0: Working, 1: Deleted');

            $table->index('admin_id', 'admin_IDX');
            $table->index('user_id', 'user_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_records', function (Blueprint $table) {
            $table->dropIndex(['user_IDX', 'admin_IDX']);
        });

        Schema::dropIfExists('device_records');
    }
};
