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
        Schema::create('laptop_records', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('user_id')->comment('reference for the user table');
            $table->integer('industry_id')->comment('reference for the industry table');
            $table->integer('company_id')->comment('reference for the company table');
            $table->integer('company_parent_id')->comment('reference for the company table with only parent available');
            $table->string('name', 50);
            $table->string('brand', 50);
            $table->text('specification')->comment('store into json format in multiple specification');
            $table->decimal('amount', 10, 3);
            $table->string('authorise_person', 50)->comment('Authorise person who can give access or buying permission');
            $table->date('order_date');
            $table->string('rmn');
            $table->string('product_id', 25);
            $table->string('sn', 15);
            $table->string('model', 20);
            $table->tinyInteger('status', 1)->comment('1: Active, 0: De Active');
            $table->timestamps();
            $table->timestamp('deleted_at', 1)->comment('1: deleted, 0: working');

            $table->index( ['industry_id', 'company_id', 'company_parent_id'], 'industry_company_IDX');
            $table->index('admin_id', 'admin_IDX');
            $table->index('status', 'status_IDX');
            $table->index('user_id', 'user_id_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laptop_records', function (Blueprint $table) {
            $table->dropIndex(['industry_company_IDX', 'status_IDX', 'user_id_IDX', 'admin_IDX']);
        });

        Schema::dropIfExists('laptop_records');
    }
};
