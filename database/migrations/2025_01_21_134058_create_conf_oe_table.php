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
        Schema::create('conf_oe', function (Blueprint $table) {
            $table->bigIncrements('OE_ID');
            $table->string('OE_ORGAO', 255);
            $table->integer('OE_VIGENCIA');
            $table->string('OE_PORTFOLIO', 255);
            $table->string('OE_UNICO', 255);
            $table->string('OE_OE', 500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conf_oe');
    }
};
