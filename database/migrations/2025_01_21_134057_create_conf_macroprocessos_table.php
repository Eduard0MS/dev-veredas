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

        Schema::create('conf_macroprocessos', function (Blueprint $table) {
            $table->bigIncrements('MP_ID');
            $table->string('MP_ORGAO', 255);
            $table->integer('MP_VIGENCIA');
            $table->string('MP_PORTFOLIO', 255);
            $table->string('MP_UNICO', 255);
            $table->string('MP_MP', 500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conf_macroprocessos');
    }
};
