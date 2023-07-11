<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dept_station')->unique();
            $table->dateTime('dept_time');
            $table->unsignedSmallInteger('train_num');
            $table->string('arr_station');
            $table->dateTime('arr_time');
          
            $table->dateTime('chg_over_time')->nullable();
            $table->enum('status',['Un-assigned','Assigned','In-progress','Completed','Expired']);
            $table->dateTime('track_time')->nullable();
            $table->unsignedInteger('chg_overs')->nullable();

            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unsignedBigInteger('dutyRoster_id');
            $table->foreign('dutyRoster_id')->references('id')->on('duty_rosters')->onDelete('cascade');

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
        Schema::dropIfExists('job_cards');
    }
};
