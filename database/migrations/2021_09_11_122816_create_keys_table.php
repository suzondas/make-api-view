<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->string('option', 2)->nullable();
            $table->unsignedBigInteger('reference_api_id')->nullable();
            $table->string('reference_api_response_key')->nullable();
            $table->unsignedBigInteger('apis_id');
            $table->foreign('apis_id')->references('id')->on('apis')->onDelete('cascade');
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
        Schema::dropIfExists('keys');
    }
}
