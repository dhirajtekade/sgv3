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
        Schema::create('eventdata', function (Blueprint $table) {
            $table->id();
            $table->string('department')->nullable()->default('Samanghar');
            $table->string('event_name')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->date('event_start_date')->nullable();
            $table->date('event_end_date')->nullable();
            $table->string('event_location')->nullable();
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
        Schema::dropIfExists('eventdata');
    }
};
