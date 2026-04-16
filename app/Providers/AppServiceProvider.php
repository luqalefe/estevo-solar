<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\ExtracaoContaLuzService;
use App\Services\ParametrosCalculo;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ParametrosCalculo::class, function ($app) {
            $p = $app['config']->get('solar.parametros');

            return new ParametrosCalculo(
                hsp: (float) $p['hsp'],
                eficiencia: (float) $p['eficiencia'],
                tarifaKwh: (float) $p['tarifa_kwh'],
                potenciaPlacaW: (int) $p['potencia_placa_w'],
                precoPorKwp: (float) $p['preco_por_kwp'],
                margemVariacao: (float) $p['margem_variacao'],
            );
        });

        $this->app->singleton(ExtracaoContaLuzService::class, function ($app) {
            return new ExtracaoContaLuzService(
                apiKey: (string) ($app['config']->get('services.gemini.key') ?? ''),
                model: (string) $app['config']->get('services.gemini.model', 'gemini-2.5-flash'),
            );
        });
    }

    public function boot(): void
    {
        //
    }
}
