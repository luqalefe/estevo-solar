<?php

declare(strict_types=1);

namespace App\Services;

final readonly class ResultadoCalculo
{
    public function __construct(
        public float $potenciaKwp,
        public int $numPlacas,
        public float $custoMinimo,
        public float $custoMaximo,
        public float $economiaMensal,
        public float $paybackAnos,
    ) {}
}
