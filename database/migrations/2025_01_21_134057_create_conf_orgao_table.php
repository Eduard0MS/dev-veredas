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
        Schema::create('conf_orgao', function (Blueprint $table) {
            $table->id('CONF_ORGAO_ID'); // BIGINT auto-increment
            $table->string('CONF_ORGAO_ABREVIATURA', 15);
            $table->string('CONF_ORGAO_NOME', 255);
            $table->text('CONF_ORGAO_ADMIN');
            $table->timestamps();  // se quiser colunas created_at/updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conf_orgao');
    }
};
