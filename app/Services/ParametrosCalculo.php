<?php

declare(strict_types=1);

namespace App\Services;

final readonly class ParametrosCalculo
{
    public function __construct(
        public float $hsp,
        public float $eficiencia,
        public float $tarifaKwh,
        public int $potenciaPlacaW,
        public float $precoPorKwp,
        public float $margemVariacao,
    ) {}

    public static function acre(): self
    {
        return new self(
            hsp: 4.5,
            eficiencia: 0.80,
            tarifaKwh: 0.92,
            potenciaPlacaW: 550,
            precoPorKwp: 4800.0,
            margemVariacao: 0.10,
        );
    }
}
