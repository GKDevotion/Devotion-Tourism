<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('industry_id');
            $table->string('username', 191)->unique();
            $table->string('first_name', 25);
            $table->string('middle_name', 25);
            $table->string('last_name', 25);
            $table->string('email', 191)->unique();
            $table->string('mobile_number', 15)->comment('admin contact detail');
            $table->tinyInteger('gender')->comment('1: Male, 2: Fe-Male, 3: Trans');
            $table->tinyInteger('religion_id', 3);
            $table->integer('continent_id');
            $table->integer('country_id');
            $table->integer('state_id');
            $table->integer('city_id');
            $table->integer('address', 100);
            $table->integer('zipcode');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('is_assign_super_admin')->comment('1: Yes, 0: No');
            $table->rememberToken();
            $table->tinyInteger('status')->comment('1: Active, 0: De-Active');
            $table->timestamps();

            $table->index('industry_id', 'industry_IDX');
            $table->index('company_id', 'company_IDX');
        });

        // Set the table engine to InnoDB
        DB::statement('ALTER TABLE admins ENGINE = InnoDB');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('example', function (Blueprint $table) {
            $table->dropIndex(['industry_IDX', 'company_IDX']);
        });
        Schema::dropIfExists('admins');
    }
}
