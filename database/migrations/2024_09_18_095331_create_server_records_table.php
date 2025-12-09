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
        Schema::create('server_records', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('user_id')->comment('reference for the user table');
            $table->string('company', 50);
            $table->text('configuration')->comment('store all record in json format');
            $table->decimal('charge', 5, 2);
            $table->string('portal_link', 100);
            $table->string('login_id', 50);
            $table->string('password', 50);
            $table->string('web', 50);
            $table->string('authorise_person', 50);
            $table->tinyInteger('status', 1)->comment('1: Active, 0: De-Active');
            $table->timestamps();
            $table->tinyInteger('deleted_at', 1)->comment('0: Working, 1: Deleted');

            $table->index( ['user_id', 'admin_id'], 'user_IDX' );
            $table->index('status', 'status_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('server_records', function (Blueprint $table) {
            $table->dropIndex(['status_IDX', 'user_IDX']);
        });

        Schema::dropIfExists('server_records');
    }
};
