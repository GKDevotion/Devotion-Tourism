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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->string('unique_id')->comment('unique inquiry number');
            $table->integer('industry_id')->comment('reference for the industry table');
            $table->integer('company_id')->comment('reference for the company table');
            $table->string('name', 50);
            $table->string('email_id', 50);
            $table->string('mobile', 15);
            $table->string('company', 50);
            $table->string('designation', 50);
            $table->string('address', 200);
            $table->string('country', 30);
            $table->tinyInteger('status', 1)->comment('0: Disabled, 1: Enabled');
            $table->timestamps();

            $table->index( ['industry_IDX', 'company_IDX'], 'industry_company_IDX');
            $table->index('admin_id', 'admin_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropIndex(['industry_company_IDX', 'admin_IDX']);
        });

        Schema::dropIfExists('inquiries');
    }
};
