<?php

declare(strict_types=1);

namespace App\Services;

final readonly class SolarCalculoService
{
    private const DIAS_NO_MES = 30;

    public function __construct(private ParametrosCalculo $params) {}

    public function calcular(int $consumoKwh): ResultadoCalculo
    {
        $potenciaKwp = $consumoKwh / (self::DIAS_NO_MES * $this->params->hsp * $this->params->eficiencia);
        $numPlacas = (int) ceil($potenciaKwp * 1000 / $this->params->potenciaPlacaW);

        $custoMedio = $potenciaKwp * $this->params->precoPorKwp;
        $custoMinimo = $custoMedio * (1 - $this->params->margemVariacao);
        $custoMaximo = $custoMedio * (1 + $this->params->margemVariacao);

        $economiaMensal = $consumoKwh * $this->params->tarifaKwh;
        $paybackAnos = $custoMedio / ($economiaMensal * 12);

        return new ResultadoCalculo(
            potenciaKwp: $potenciaKwp,
            numPlacas: $numPlacas,
            custoMinimo: $custoMinimo,
            custoMaximo: $custoMaximo,
            economiaMensal: $economiaMensal,
            paybackAnos: $paybackAnos,
        );
    }

    public function gerarMensagemWhatsapp(
        ResultadoCalculo $resultado,
        string $nome,
        int $consumoKwh,
    ): string {
        $kwp = number_format($resultado->potenciaKwp, 2, ',', '.');
        $custoMin = 'R$ '.number_format($resultado->custoMinimo, 2, ',', '.');
        $custoMax = 'R$ '.number_format($resultado->custoMaximo, 2, ',', '.');
        $economia = 'R$ '.number_format($resultado->economiaMensal, 2, ',', '.');
        $payback = (int) round($resultado->paybackAnos);

        return <<<MSG
            Olá! Gostaria de um orçamento de energia solar.

            Nome: {$nome}
            Consumo mensal: {$consumoKwh} kWh

            Estimativa calculada no site:
            • {$resultado->numPlacas} placas solares
            • Sistema de {$kwp} kWp
            • Investimento estimado: {$custoMin} a {$custoMax}
            • Economia mensal: {$economia}
            • Retorno em aprox. {$payback} anos

            Aguardo contato!
            MSG;
    }
}
