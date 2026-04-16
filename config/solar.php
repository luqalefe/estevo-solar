<?php

declare(strict_types=1);

return [
    'empresa' => [
        'nome' => env('EMPRESA_NOME', 'Solar Acre Demo'),
        'whatsapp' => env('EMPRESA_WHATSAPP', '5568999999999'),
    ],
    'parametros' => [
        'hsp' => env('SOLAR_HSP', 4.5),
        'eficiencia' => env('SOLAR_EFICIENCIA', 0.80),
        'tarifa_kwh' => env('SOLAR_TARIFA_KWH', 0.92),
        'potencia_placa_w' => env('SOLAR_POTENCIA_PLACA_W', 550),
        'preco_por_kwp' => env('SOLAR_PRECO_POR_KWP', 4800.0),
        'margem_variacao' => env('SOLAR_MARGEM_VARIACAO', 0.10),
    ],
];
