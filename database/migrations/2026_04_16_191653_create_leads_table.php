<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email');
            $table->string('telefone', 20);
            $table->unsignedInteger('consumo_kwh');

            $table->decimal('potencia_kwp', 6, 2);
            $table->unsignedSmallInteger('num_placas');
            $table->decimal('custo_minimo', 12, 2);
            $table->decimal('custo_maximo', 12, 2);
            $table->decimal('economia_mensal', 12, 2);
            $table->decimal('payback_anos', 5, 2);

            $table->timestamp('consentimento_lgpd_em');
            $table->timestamp('whatsapp_clicado_em')->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
