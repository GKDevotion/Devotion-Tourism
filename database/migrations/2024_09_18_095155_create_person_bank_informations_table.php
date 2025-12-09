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
        Schema::create('person_bank_informations', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('user_id')->comment('reference for the user table');
            $table->integer('person_id')->comment('reference for the person table');
            $table->string('bank_name', 50);
            $table->string('holder_name', 50);
            $table->integer('account_no', 20);
            $table->string('ifsc_code', 15);
            $table->string('micr_code', 15);
            $table->string('branch_code', 15);
            $table->string('email_id', 50);
            $table->string('phone', 10);
            $table->string('address', 200);
            $table->timestamps();

            $table->index('person_id', 'person_IDX');
            $table->index('status', 'status_IDX');
            $table->index( ['admin_id', 'user_IDX'], 'user_IDX' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('person_bank_informations', function (Blueprint $table) {
            $table->dropIndex(['person_IDX', 'status_IDX', 'user_IDX']);
        });

        Schema::dropIfExists('person_bank_informations');
    }
};
