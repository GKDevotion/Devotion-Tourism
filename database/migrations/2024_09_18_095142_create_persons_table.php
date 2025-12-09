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
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('user_id')->comment('reference for the user table');
            $table->integer('parent_id')->comment('any person available in any client so we can update there unique id here');
            $table->integer('industry_id')->comment('reference for the industry table');
            $table->integer('company_id')->comment('reference for the company table');
            $table->integer('department_id')->comment('reference for the department table');
            $table->integer('business_id')->comment('reference for the business table');
            $table->integer('shift_id')->comment('reference for the shift table');
            $table->string('unique_id')->comment('unique identity number');
            $table->string('asset_no', 15)->comment('empployee or customer provide extra document identity');
            $table->string('first_name', 30);
            $table->string('middle_name', 30);
            $table->string('last_name', 30);
            $table->string('email_id', 50);
            $table->string('company_mobile_number', 15)->comment('company provide contact number');
            $table->string('personal_mobile_number', 15)->comment('personal contact number');
            $table->tinyInteger('type', 1)->comment('1: Employee, 2: Customer, 3: Client');
            $table->tinyInteger('gender', 1)->comment('1: Male, 2: Fe-Male, 3: Trans');
            $table->integer('religion_id')->comment('reference for the religion table');
            $table->integer('continent_id')->comment('reference for the continent table');
            $table->integer('country_id')->comment('reference for the country table');
            $table->integer('state_id')->comment('reference for the state table');
            $table->integer('city_id')->comment('reference for the city table');
            $table->integer('position_id')->comment('reference for the position table');
            $table->string('avtar', 100);
            $table->date('birth_day');
            $table->date('joining_date');
            $table->text('social_medias')->comment('store json response of social media');
            $table->tinyInteger('status', 1)->comment('0: Disabled, 1:Enabled');
            $table->text('other_info')->comment('store any other information');
            $table->timestamps();
            $table->tinyInteger('deleted_at', 1)->comment('0: Working, 1: Deleted');

            $table->index('type', 'type_IDX');
            $table->index('gender', 'gender_IDX');
            $table->index('user_id', 'user_IDX');
            $table->index('religion_id', 'religion_IDX');
            $table->index('status', 'status_IDX');
            $table->index('admin_id', 'admin_IDX');
            $table->index('shift_id', 'shift_IDX');
            $table->index( ['industry_id', 'company_id', 'business_id'], 'company_IDX');
            $table->index( ['continent_id', 'country_id', 'state_id', 'city_id'], 'continent_IDX');
            $table->index('department_id', 'department_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropIndex(['gender_IDX', 'type_IDX', 'continent_IDX', 'admin_IDX', 'user_IDX', 'religion_IDX', 'status_IDX', 'shift_IDX', 'company_IDX', 'department_IDX']);
        });

        Schema::dropIfExists('persons');
    }
};
