<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    /** @use HasFactory<\Database\Factories\LeadFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'consumo_kwh' => 'integer',
            'num_placas' => 'integer',
            'potencia_kwp' => 'decimal:2',
            'custo_minimo' => 'decimal:2',
            'custo_maximo' => 'decimal:2',
            'economia_mensal' => 'decimal:2',
            'payback_anos' => 'decimal:2',
            'consentimento_lgpd_em' => 'datetime',
            'whatsapp_clicado_em' => 'datetime',
        ];
    }
}
