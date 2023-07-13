<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('task_name');
            $table->uuid('project_id');
            $table->foreign('project_id')->references('id')->on('project_spaces');
            $table->string('task_status');
            $table->string('task_priority');
            $table->date('task_deadline');
            $table->date('task_completion_date');
            $table->string('task_overdue');
            $table->date('task_creation_date');







            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_tasks');
    }
};
