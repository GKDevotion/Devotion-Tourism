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
        Schema::create('mobile_records', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('user_id')->comment('reference for the user table');
            $table->integer('industry_id')->comment('reference for the industry table');
            $table->integer('company_id')->comment('reference for the company table');
            $table->integer('company_parent_id')->comment('reference for the parent company');
            $table->string('name', 200);
            $table->string('brand', 50);
            $table->text('specification');
            $table->decimal('amount', 10, 3);
            $table->string('authorise_person', 50);
            $table->date('order_date');
            $table->string('imei', 25);
            $table->string('sn', 15);
            $table->tinyInteger('status', 1)->comment('1: Active, 0: De Active');
            $table->timestamps();
            $table->tinyInteger('deleted_at', 1)->comment('0: Working, 1: Deleted');

            $table->index('user_id', 'user_IDX');
            $table->index('status', 'status_IDX');
            $table->index('admin_id', 'admin_IDX');
            $table->index( ['industry_id', 'company_id', 'company_parent_id'], 'industry_company_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mobile_records', function (Blueprint $table) {
            $table->dropIndex(['user_IDX', 'status_IDX', 'industry_company_IDX', 'admin_IDX']);
        });

        Schema::dropIfExists('mobile_records');
    }
};
