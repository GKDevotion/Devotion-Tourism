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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('industry_id')->comment('reference for the industry table');
            $table->integer('parent_id')->comment('is child company under any parent one');
            $table->string('name', 50);
            $table->string('slug', 50);
            $table->string('website_link', 50)->comment('company host URL');
            $table->string('hax_code', 7)->comment('color hax code');
            $table->string('rgb_code', 15)->comment('color rgb code');
            $table->tinyInteger('sort_order', 3)->comment('sort ordering');
            $table->tinyInteger('status', 1)->comment('0: Disabled, 1: Enable');
            $table->timestamps();
            $table->tinyInteger('deleted_at', 1);

            $table->index('industry_id', 'fk_company_industry_id');
            $table->index('admin_id', 'admin_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropIndex(['industry_id', 'admin_IDX']);
        });

        Schema::dropIfExists('companies');
    }
};
