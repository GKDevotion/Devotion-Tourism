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
        Schema::create('corporate_emails', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('user_id')->comment('reference for the user table');
            $table->integer('company_id')->comment('reference for the company table');
            $table->integer('company_parent_id')->comment('reference for the master company');
            $table->integer('industry_id')->comment('reference for the industry table');
            $table->string('first_name', 20);
            $table->string('last_name', 20);
            $table->string('email', 50);
            $table->string('primary_email', 50);
            $table->tinyInteger('status', 1)->comment('0: De-Active, 1: Active');
            $table->timestamps();
            $table->tinyInteger('deleted_at', 1)->comment('0: Working, 1: Deleted');

            $table->index('user_id', 'user_IDX');
            $table->index( ['company_id', 'company_parent_id'], 'company_IDX');
            $table->index('admin_id', 'admin_IDX');
            $table->index('industry_id', 'industry_IDX');
            $table->index('status', 'status_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('corporate_emails', function (Blueprint $table) {
            $table->dropIndex(['user_IDX', 'company_IDX', 'industry_IDX', 'admin_IDX', 'status_IDX']);
        });

        Schema::dropIfExists('corporate_emails');
    }
};
