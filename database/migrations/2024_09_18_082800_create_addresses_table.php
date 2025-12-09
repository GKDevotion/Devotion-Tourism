<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->comment('reference for the admin table');
            $table->integer('person_id')->comment('reference for the person table');
            $table->integer('client_id')->comment('reference for the client table');
            $table->string('unique_id', 50)->unique();
            $table->string('name', 50);
            $table->string('email_id', 50)->unique();
            $table->string('contact_number', 15);
            $table->string('address', 200);
            $table->string('continent_id')->comment('reference for the continent table');
            $table->string('country_id')->comment('reference for the country table');
            $table->string('city_id')->comment('reference for the city table');
            $table->string('zipcode', 10);
            $table->tinyInteger('type', 1)->comment('1: Permanent, 2: Temporary, 3: Business');;
            $table->text('description');
            $table->timestamps();
            $table->timestamp('deleted_at')->comment('0: Working, 1: Deleted');
            $table->index('person_id', 'person_IDX');
            $table->index('client_id', 'client_IDX');
            $table->index('type', 'type_IDX');
            $table->index( ['continent_id', 'country_id', 'state_id', 'city_id'], 'continent_IDX');
            $table->index('admin_id', 'admin_IDX');
            $table->comment('store customer, employee, client personal address with permanent, temporary & business related');

            // $table->decimal('column_name', $precision, $scale);
            // $table->enum('column_name', ['value1', 'value2', 'value3']);
        });

        // Set the table engine to InnoDB
        DB::statement('ALTER TABLE addresses ENGINE = InnoDB');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex(['person_IDX', 'client_IDX', 'type_IDX', 'continent_IDX', 'admin_IDX']);
        });

        Schema::dropIfExists('addresses');
    }
};
