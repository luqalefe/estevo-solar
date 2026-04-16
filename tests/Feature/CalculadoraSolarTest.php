<?php

use App\Livewire\CalculadoraSolar;
use App\Models\Lead;
use App\Services\ExtracaoContaLuzService;
use App\Services\ResultadoExtracao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('landing / renderiza pitch institucional da Estevo Tech', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Estevo Tech', false)
        ->assertSee('energia solar', false);
});

it('rota /demo renderiza a calculadora Livewire', function () {
    $this->get('/demo')
        ->assertOk()
        ->assertSeeLivewire(CalculadoraSolar::class);
});

it('cria lead e mostra resultado com dados válidos', function () {
    Livewire::test(CalculadoraSolar::class)
        ->set('nome', 'Lucas Estevo')
        ->set('email', 'lucas@estevo.tech')
        ->set('telefone', '68992582319')
        ->set('consumoKwh', 300)
        ->set('consentimentoLgpd', true)
        ->call('calcular')
        ->assertHasNoErrors()
        ->assertSee('Lucas Estevo')
        ->assertSee('Placas solares')
        ->assertSee('2,78')
        ->assertSee('R$ 276,00')
        ->assertSee('WhatsApp');

    expect(Lead::count())->toBe(1);

    $lead = Lead::first();
    expect($lead->nome)->toBe('Lucas Estevo')
        ->and($lead->email)->toBe('lucas@estevo.tech')
        ->and($lead->telefone)->toBe('68992582319')
        ->and($lead->consumo_kwh)->toBe(300)
        ->and($lead->num_placas)->toBe(6)
        ->and((float) $lead->potencia_kwp)->toEqualWithDelta(2.78, 0.01)
        ->and($lead->consentimento_lgpd_em)->not->toBeNull();
});

it('bloqueia submit sem campos obrigatórios e não persiste lead', function () {
    Livewire::test(CalculadoraSolar::class)
        ->call('calcular')
        ->assertHasErrors(['nome', 'email', 'telefone', 'consumoKwh', 'consentimentoLgpd']);

    expect(Lead::count())->toBe(0);
});

it('exige consentimento LGPD marcado', function () {
    Livewire::test(CalculadoraSolar::class)
        ->set('nome', 'Maria')
        ->set('email', 'maria@example.com')
        ->set('telefone', '(68) 99999-0000')
        ->set('consumoKwh', 250)
        ->set('consentimentoLgpd', false)
        ->call('calcular')
        ->assertHasErrors(['consentimentoLgpd']);

    expect(Lead::count())->toBe(0);
});

it('aceita telefone com e sem máscara', function () {
    foreach (['68992582319', '(68) 99258-2319', '68 99258-2319', '6899258231'] as $tel) {
        Livewire::test(CalculadoraSolar::class)
            ->set('telefone', $tel)
            ->call('calcular')
            ->assertHasNoErrors('telefone');
    }
});

it('rejeita telefone com menos dígitos que o mínimo', function () {
    Livewire::test(CalculadoraSolar::class)
        ->set('telefone', '123456')
        ->call('calcular')
        ->assertHasErrors('telefone');
});

it('preenche consumo automaticamente quando foto da conta é lida com sucesso', function () {
    $this->mock(ExtracaoContaLuzService::class)
        ->shouldReceive('extrair')
        ->once()
        ->andReturn(ResultadoExtracao::ok(consumoKwh: 342, mesReferencia: null, confianca: 0.9));

    Livewire::test(CalculadoraSolar::class)
        ->set('foto', UploadedFile::fake()->create('conta.jpg', 100, 'image/jpeg'))
        ->assertSet('consumoKwh', 342)
        ->assertSee('342 kWh');
});

it('mostra mensagem de erro quando OCR não encontra consumo', function () {
    $this->mock(ExtracaoContaLuzService::class)
        ->shouldReceive('extrair')
        ->once()
        ->andReturn(ResultadoExtracao::falha('Não localizamos o consumo. Digite manualmente.'));

    Livewire::test(CalculadoraSolar::class)
        ->set('foto', UploadedFile::fake()->create('conta.jpg', 100, 'image/jpeg'))
        ->assertSet('consumoKwh', null)
        ->assertSee('Digite manualmente');
});

it('rejeita consumo fora da faixa aceitável', function () {
    Livewire::test(CalculadoraSolar::class)
        ->set('nome', 'Teste')
        ->set('email', 'teste@example.com')
        ->set('telefone', '(68) 99999-0000')
        ->set('consumoKwh', 5)
        ->set('consentimentoLgpd', true)
        ->call('calcular')
        ->assertHasErrors(['consumoKwh']);
});
