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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('person_id')->comment('reference for the person table');
            $table->unique('unique_id');
            $table->string('name', 50)->comment('address/business name');
            $table->string('email_id', 50);
            $table->string('contact_number', 15);
            $table->string('website', 100);
            $table->integer('industry_id')->comment('reference for the industry table');
            $table->text('description');
            $table->string('logo')->comment('identify the business thumbnail');
            $table->date('establish_date')->comment('business established');
            $table->tinyInteger('status')->comment('1:Active, 0: De-active');
            $table->timestamps();

            $table->index('person_id', 'person_IDX');
            $table->index('business_type', 'business_type_IDX');
            $table->index('status', 'status_IDX');
            $table->index('admin_id', 'admin_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropIndex(['person_IDX', 'business_type_IDX', 'status_IDX', 'admin_IDX']);
        });

        Schema::dropIfExists('businesses');
    }
};
