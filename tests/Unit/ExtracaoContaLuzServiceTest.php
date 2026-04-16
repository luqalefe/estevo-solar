<?php

use App\Services\ExtracaoContaLuzService;
use Illuminate\Support\Facades\Http;

function respostaGemini(array $dados): array
{
    return [
        'candidates' => [[
            'content' => [
                'parts' => [['text' => json_encode($dados)]],
            ],
        ]],
    ];
}

it('extrai consumo kWh quando Gemini retorna sucesso', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response(respostaGemini([
            'consumo_kwh' => 342,
            'mes_referencia' => '03/2026',
            'confianca' => 0.97,
            'falhou' => false,
        ])),
    ]);

    $fixture = base_path('tests/fixtures/conta-fake.jpg');
    $service = new ExtracaoContaLuzService(apiKey: 'test-key');
    $resultado = $service->extrair($fixture);

    expect($resultado->sucesso)->toBeTrue()
        ->and($resultado->consumoKwh)->toBe(342)
        ->and($resultado->mesReferencia)->toBe('03/2026')
        ->and($resultado->confianca)->toBe(0.97);
});

it('retorna falha quando Gemini reporta falhou=true', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response(respostaGemini([
            'consumo_kwh' => 0,
            'confianca' => 0.0,
            'falhou' => true,
            'motivo_falha' => 'imagem borrada',
        ])),
    ]);

    $fixture = base_path('tests/fixtures/conta-fake.jpg');
    $service = new ExtracaoContaLuzService(apiKey: 'test-key');
    $resultado = $service->extrair($fixture);

    expect($resultado->sucesso)->toBeFalse()
        ->and($resultado->erro)->toContain('borrada');
});

it('retorna falha quando Gemini retorna HTTP 500', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response('internal error', 500),
    ]);

    $fixture = base_path('tests/fixtures/conta-fake.jpg');
    $service = new ExtracaoContaLuzService(apiKey: 'test-key');
    $resultado = $service->extrair($fixture);

    expect($resultado->sucesso)->toBeFalse()
        ->and($resultado->erro)->toContain('Tente novamente');
});

it('retorna falha quando arquivo não existe sem chamar API', function () {
    Http::fake();

    $service = new ExtracaoContaLuzService(apiKey: 'test-key');
    $resultado = $service->extrair('/caminho/inexistente.jpg');

    expect($resultado->sucesso)->toBeFalse()
        ->and($resultado->erro)->toContain('não encontrad');

    Http::assertNothingSent();
});

it('retorna falha quando API key não está configurada', function () {
    Http::fake();

    $fixture = base_path('tests/fixtures/conta-fake.jpg');
    $service = new ExtracaoContaLuzService(apiKey: '');
    $resultado = $service->extrair($fixture);

    expect($resultado->sucesso)->toBeFalse()
        ->and($resultado->erro)->toContain('não configurado');

    Http::assertNothingSent();
});

it('envia requisição com payload correto (modelo, prompt, imagem base64)', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response(respostaGemini([
            'consumo_kwh' => 200,
            'confianca' => 0.9,
            'falhou' => false,
        ])),
    ]);

    $fixture = base_path('tests/fixtures/conta-fake.jpg');
    $service = new ExtracaoContaLuzService(apiKey: 'sk-test', model: 'gemini-2.5-flash');
    $service->extrair($fixture);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'gemini-2.5-flash:generateContent')
            && str_contains($request->url(), 'key=sk-test')
            && isset($request->data()['contents'][0]['parts'][0]['inline_data']['data'])
            && $request->data()['generationConfig']['responseMimeType'] === 'application/json';
    });
});
