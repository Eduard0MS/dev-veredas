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
        Schema::create('conf_kr', function (Blueprint $table) {
            $table->bigInteger('KR_ID')->primary();  // <-- sem increments
            $table->string('KR_ORGAO');
            $table->integer('KR_VIGENCIA');
            $table->string('KR_PORTFOLIO');
            $table->string('KR_UNICO');
            $table->string('KR_KR', 1000);  // Ou text(), para caber o texto maior
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conf_kr');
    }
};
