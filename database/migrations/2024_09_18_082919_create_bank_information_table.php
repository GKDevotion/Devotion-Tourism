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
        Schema::create('bank_information', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('user_id')->comment('reference for the user table');
            $table->integer('person_id')->comment('reference for the person table');
            $table->string('bank_name', 50);
            $table->string('holder_name', 50);
            $table->string('account_no', 50);
            $table->string('ifcs_code', 10);
            $table->string('micr_code', 10);
            $table->string('branch_code', 10);
            $table->string('email_id', 50);
            $table->string('phone', 10);
            $table->tex('address');
            $table->timestamps();

            $table->index('person_id', 'person_IDX');
            $table->index( ['admin_id', 'user_id'], 'admin_IDX');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_information', function (Blueprint $table) {
            $table->dropIndex(['person_IDX', 'admin_IDX']);
        });

        Schema::dropIfExists('bank_information');
    }
};
