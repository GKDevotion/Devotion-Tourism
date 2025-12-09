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
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->id();
            $table->string('class_name', 30)->comment('controller(class, routes) name of module');
            $table->integer('parent_id')->comment('as a reference menu');
            $table->string('name', 40)->comment('name to be displayed in menu');
            $table->string('slug', 40)->comment('use for url or side bar menu');
            $table->string('group_name', 25)->comment('user can permission via group guard');
            $table->string('icon',40)->comment('menu icon to be used');
            $table->tinyInteger('status', 1)->comment('0: Disabled, 1: Enabled');
            $table->tinyInteger('sort_order', 3)->comment('sort ordering');
            $table->timestamps();

            $table->index('person_id', 'person_IDX');
            $table->index('status', 'status_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_menus', function (Blueprint $table) {
            $table->dropIndex(['person_IDX', 'status_IDX']);
        });

        Schema::dropIfExists('admin_menus');
    }
};
