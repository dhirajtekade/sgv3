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
        Schema::create('token_data', function (Blueprint $table) {
            $table->id();
            $table->string('fk_mhtdata_id')->nullable();
            $table->string('fk_event_id')->nullable();
            $table->integer('each_token_no')->nullable();//with varchar it will create issue while order by desc, and hence get last token value and set new token number incremented by one
            $table->string('no_luggage')->nullable();
            $table->string('qr_path')->nullable();
            $table->integer('checkout_status')->default(0)->comment('0:checkin, 1:checkout, 2:partial checkout');
            $table->softDeletes();
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
        Schema::dropIfExists('token_data');
    }
};
