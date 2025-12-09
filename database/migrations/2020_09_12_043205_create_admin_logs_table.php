<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id');
            $table->integer('user_id')->comment('user for which add, modification or delete logs recorded');
            $table->string('class_name', 50)->comment('controller(class, routes) name of module');
            $table->string('group_name', 50)->comment('	Menu Group Guard Name');
            $table->string('item_name', 50)->comment('controller item name');
            $table->string('table_name', 30)->comment('database table name');
            $table->string('table_field')->comment('name of table field');
            $table->integer('primary_id', 3)->comment('reference table primary id');
            $table->integer('parent_table_pk_id	')->comment('store parent table primary key value');
            $table->string('action', 1)->comment('A: Add, E: Edit, D: Delete');
            $table->string('log_ip');
            $table->string('description', 200);
            $table->timestamps();

            $table->index('user_id', 'user_IDX');
            $table->index('admin_id', 'admin_IDX');
        });

        // Set the table engine to InnoDB
        DB::statement('ALTER TABLE admin_logs ENGINE = InnoDB');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('example', function (Blueprint $table) {
            $table->dropIndex(['user_IDX', 'admin_IDX']);
        });
        Schema::dropIfExists('admin_logs');
    }
}
