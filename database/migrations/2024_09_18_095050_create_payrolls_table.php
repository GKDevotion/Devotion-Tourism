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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('user_id')->comment('reference for the user table');
            $table->integer('person_id')->comment('reference for the person table');
            $table->date('pay_date')->comment('payment allocate date');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('dearness_allowance', 10, 2);
            $table->decimal('deduction_provident_fund', 10, 2);
            $table->decimal('net_salary', 10, 2);
            $table->text('note');
            $table->tinyInteger('is_advance_pay', 1)->comment('1: Yes, 0: No');
            $table->tinyInteger('status')->comment('0: Disabled, 1: Enabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
