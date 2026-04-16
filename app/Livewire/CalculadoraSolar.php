<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Lead;
use App\Services\ExtracaoContaLuzService;
use App\Services\SolarCalculoService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class CalculadoraSolar extends Component
{
    use WithFileUploads;

    #[Validate('required|string|min:2|max:100')]
    public string $nome = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate(
        rule: ['required', 'string', 'regex:/^\(?\d{2}\)?[\s-]?9?\d{4}[\s-]?\d{4}$/'],
        message: [
            'telefone.required' => 'Informe seu WhatsApp.',
            'telefone.regex' => 'WhatsApp inválido. Ex: (68) 99258-2319 ou 68992582319.',
        ],
    )]
    public string $telefone = '';

    #[Validate('required|integer|min:50|max:10000')]
    public ?int $consumoKwh = null;

    #[Validate('accepted')]
    public bool $consentimentoLgpd = false;

    public ?Lead $lead = null;

    public ?string $mensagemWhatsapp = null;

    #[Validate(['nullable', 'image', 'max:6144'])]
    public $foto = null;

    public bool $processandoFoto = false;

    public ?string $mensagemExtracao = null;

    public function updatedFoto(): void
    {
        if (! $this->foto instanceof TemporaryUploadedFile) {
            return;
        }

        $this->processandoFoto = true;
        $this->mensagemExtracao = null;

        $resultado = app(ExtracaoContaLuzService::class)
            ->extrair($this->foto->getRealPath());

        if ($resultado->sucesso) {
            $this->consumoKwh = $resultado->consumoKwh;
            $this->mensagemExtracao = "Detectamos {$resultado->consumoKwh} kWh na sua conta. Confira se está certo.";
        } else {
            $this->mensagemExtracao = $resultado->erro;
        }

        $this->foto = null;
        $this->processandoFoto = false;
    }

    public function calcular(SolarCalculoService $service): void
    {
        $this->validate([
            'nome' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:255',
            'telefone' => ['required', 'string', 'regex:/^\(?\d{2}\)?[\s-]?9?\d{4}[\s-]?\d{4}$/'],
            'consumoKwh' => 'required|integer|min:50|max:10000',
            'consentimentoLgpd' => 'accepted',
        ]);

        $resultado = $service->calcular($this->consumoKwh);

        $this->lead = Lead::create([
            'nome' => $this->nome,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'consumo_kwh' => $this->consumoKwh,
            'potencia_kwp' => $resultado->potenciaKwp,
            'num_placas' => $resultado->numPlacas,
            'custo_minimo' => $resultado->custoMinimo,
            'custo_maximo' => $resultado->custoMaximo,
            'economia_mensal' => $resultado->economiaMensal,
            'payback_anos' => $resultado->paybackAnos,
            'consentimento_lgpd_em' => now(),
        ]);

        $this->mensagemWhatsapp = $service->gerarMensagemWhatsapp(
            resultado: $resultado,
            nome: $this->nome,
            consumoKwh: $this->consumoKwh,
        );
    }

    public function render()
    {
        return view('livewire.calculadora-solar');
    }
}
