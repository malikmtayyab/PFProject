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
        Schema::create('project_spaces', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('project_name');
            $table->enum('project_status',  ['In-Progress', 'Completed']);
            $table->date('project_deadline');
            $table->date('project_completion_date')->nullable();
            $table->string('overdue');
            $table->string('project_completion_percentage');
            $table->string('project_owner');
            $table->date('project_creation_date');
            $table->uuid('workspace_id');
            $table->foreign('workspace_id')->references('id')->on('work_space');
            $table->uuid('lead_id');
            $table->foreign('lead_id')->references('id')->on('users');
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
        Schema::dropIfExists('project_spaces');
    }
};
