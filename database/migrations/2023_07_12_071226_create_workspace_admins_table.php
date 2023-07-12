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
        Schema::create('workspace_admins', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->uuid('workspace_id');
            $table->foreign('workspace_id')->references('id')->on('work_space');
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
        Schema::dropIfExists('workspace_admins');
    }
};
