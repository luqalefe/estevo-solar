<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        $consumo = fake()->numberBetween(150, 800);
        $custoMedio = fake()->randomFloat(2, 8000, 40000);

        return [
            'nome' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'telefone' => '(68) 9'.fake()->numerify('####-####'),
            'consumo_kwh' => $consumo,
            'potencia_kwp' => fake()->randomFloat(2, 1.5, 8.0),
            'num_placas' => fake()->numberBetween(4, 18),
            'custo_minimo' => $custoMedio * 0.9,
            'custo_maximo' => $custoMedio * 1.1,
            'economia_mensal' => $consumo * 0.92,
            'payback_anos' => fake()->randomFloat(2, 3.5, 7.0),
            'consentimento_lgpd_em' => now(),
            'whatsapp_clicado_em' => null,
        ];
    }
}
