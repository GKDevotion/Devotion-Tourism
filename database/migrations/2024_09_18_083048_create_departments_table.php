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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('industry_id')->comment('reference for the industry table');
            $table->integer('company_id')->comment('reference for the company table');
            $table->string('name', 50);
            $table->string('slug', 50);
            $table->string('hax_code', 10);
            $table->string('rgb_code', 10);
            $table->tinyInteger('sort_order', 2);
            $table->tinyInteger('status', 1)->comment('0: De-Active, 1: Active');
            $table->timestamps();
            $table->tinyInteger('deleted_at')->comment('0: Working, 1: Deleted');

            $table->index('admin_id', 'admin_IDX');
            $table->index('industry_id', 'industry_IDX');
            $table->index('company_id', 'company_IDX');
            $table->index('status_id', 'status_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropIndex(['admin_IDX', 'industry_IDX', 'company_IDX', 'status_IDX']);
        });

        Schema::dropIfExists('departments');
    }
};
