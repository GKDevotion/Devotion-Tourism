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
        Schema::create('person_personal_informations', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('user_id')->comment('reference for the user table');
            $table->integer('person_id')->comment('reference for the person table');
            $table->integer('employee_type_id')->comment('reference for the employee type table');
            $table->integer('payment_frequency_id')->comment('reference for the payment frequency table');
            $table->integer('health_condition_id')->comment('reference for the human health condition table');
            $table->integer('marital_status_id')->comment('reference for the marital status table');
            $table->date('contract_start_date');
            $table->date('contract_end_date');
            $table->date('hire_date');
            $table->date('re_hire_date');
            $table->date('termination_date');
            $table->tinyInteger('monthly_hour', 3);
            $table->string('aadhar_card', 20);
            $table->string('pan_card', 15);
            $table->string('passport', 15);
            $table->string('driving_license', 15);
            $table->string('national_id_card', 20);
            $table->string('voter_id_card', 15);
            $table->string('utility_bills', 200);
            $table->string('bank_statement', 200);
            $table->string('agreement', 200);
            $table->string('property_receipt', 200);
            $table->string('birth_certificate', 200);
            $table->string('employee_letter', 200);
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('transport_allowance', 10, 2);
            $table->decimal('transport_benefit', 10, 2);
            $table->decimal('medical_allowance', 10, 2);
            $table->decimal('family_allowance', 10, 2);
            $table->tinyInteger('kids', 1);
            $table->string('blood_group', 5);
            $table->timestamps();

            $table->index('person_id', 'person_IDX');
            $table->index('employee_type_id', 'employee_type_IDX');
            $table->index('payment_frequency_id', 'payment_frequency_IDX');
            $table->index('marital_status_id', 'marital_status_IDX');
            $table->index('health_condition_id', 'health_condition_IDX');
            $table->index( ['admin_id', 'user_id'], 'user_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('person_personal_informations', function (Blueprint $table) {
            $table->dropIndex(['person_IDX', 'employee_type_IDX', 'payment_frequency_IDX', 'marital_status_IDX', 'health_condition_IDX', 'user_IDX']);
        });

        Schema::dropIfExists('person_personal_informations');
    }
};
