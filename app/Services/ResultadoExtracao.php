<?php

declare(strict_types=1);

namespace App\Services;

final readonly class ResultadoExtracao
{
    private function __construct(
        public bool $sucesso,
        public ?int $consumoKwh = null,
        public ?string $mesReferencia = null,
        public ?float $confianca = null,
        public ?string $erro = null,
    ) {}

    public static function ok(int $consumoKwh, ?string $mesReferencia, float $confianca): self
    {
        return new self(
            sucesso: true,
            consumoKwh: $consumoKwh,
            mesReferencia: $mesReferencia,
            confianca: $confianca,
        );
    }

    public static function falha(string $motivo): self
    {
        return new self(sucesso: false, erro: $motivo);
    }
}
