<?php

use App\Services\ParametrosCalculo;
use App\Services\ResultadoCalculo;
use App\Services\SolarCalculoService;

it('calcula orçamento para consumo de 300 kWh com parâmetros padrão do Acre', function () {
    $service = new SolarCalculoService(ParametrosCalculo::acre());

    $resultado = $service->calcular(consumoKwh: 300);

    expect($resultado)->toBeInstanceOf(ResultadoCalculo::class)
        ->and($resultado->potenciaKwp)->toEqualWithDelta(2.78, 0.01)
        ->and($resultado->numPlacas)->toBe(6)
        ->and($resultado->economiaMensal)->toEqualWithDelta(276.00, 0.01)
        ->and($resultado->custoMinimo)->toEqualWithDelta(12000.00, 1.00)
        ->and($resultado->custoMaximo)->toEqualWithDelta(14666.67, 1.00)
        ->and($resultado->paybackAnos)->toEqualWithDelta(4.03, 0.05);
});

it('aceita parâmetros customizados por cliente (multi-tenant)', function () {
    $params = new ParametrosCalculo(
        hsp: 5.0,
        eficiencia: 0.85,
        tarifaKwh: 1.00,
        potenciaPlacaW: 600,
        precoPorKwp: 5000.0,
        margemVariacao: 0.05,
    );
    $service = new SolarCalculoService($params);

    $resultado = $service->calcular(consumoKwh: 300);

    expect($resultado->potenciaKwp)->toEqualWithDelta(2.35, 0.01)
        ->and($resultado->numPlacas)->toBe(4)
        ->and($resultado->economiaMensal)->toEqualWithDelta(300.00, 0.01);
});

it('gera mensagem WhatsApp com dados do cliente e resultado formatado', function () {
    $service = new SolarCalculoService(ParametrosCalculo::acre());
    $resultado = $service->calcular(consumoKwh: 300);

    $msg = $service->gerarMensagemWhatsapp(
        resultado: $resultado,
        nome: 'Lucas',
        consumoKwh: 300,
    );

    expect($msg)
        ->toContain('Olá')
        ->toContain('Lucas')
        ->toContain('300 kWh')
        ->toContain('6 placas')
        ->toContain('2,78 kWp')
        ->toContain('R$ 12.000,00')
        ->toContain('R$ 14.666,67')
        ->toContain('R$ 276,00')
        ->toContain('4 anos');
});
