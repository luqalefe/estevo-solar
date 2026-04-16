<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExtracaoContaLuzService
{
    private const URL_BASE = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct(
        private readonly string $apiKey,
        private readonly string $model = 'gemini-2.5-flash',
    ) {}

    public function extrair(string $caminhoImagem): ResultadoExtracao
    {
        if (! is_file($caminhoImagem)) {
            return ResultadoExtracao::falha('Arquivo não encontrado.');
        }

        if ($this->apiKey === '') {
            return ResultadoExtracao::falha('Serviço de leitura não configurado.');
        }

        $mime = $this->detectarMime($caminhoImagem);
        $base64 = base64_encode((string) file_get_contents($caminhoImagem));

        try {
            $response = Http::retry(
                times: 3,
                sleepMilliseconds: fn (int $attempt) => 1000 * (2 ** ($attempt - 1)),
                when: fn (\Throwable $e) => $e instanceof ConnectionException
                    || ($e instanceof RequestException && in_array($e->response?->status(), [429, 500, 502, 503, 504], true)),
                throw: false,
            )
                ->timeout(30)
                ->throw(fn ($response, $e) => in_array($response->status(), [429, 500, 502, 503, 504], true))
                ->post(
                    self::URL_BASE."/{$this->model}:generateContent?key=".$this->apiKey,
                    $this->corpoRequisicao($mime, $base64),
                );
        } catch (\Throwable $e) {
            Log::warning('Falha ao chamar Gemini', [
                'erro' => $e->getMessage(),
                'classe' => $e::class,
            ]);

            return ResultadoExtracao::falha('Serviço temporariamente indisponível. Tente novamente em 1 minuto ou digite manualmente.');
        }

        if (! $response->ok()) {
            Log::warning('Gemini retornou erro HTTP', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ResultadoExtracao::falha('Erro ao processar imagem. Tente novamente.');
        }

        $texto = $response->json('candidates.0.content.parts.0.text');
        if (! is_string($texto)) {
            Log::warning('Gemini retornou formato inesperado', ['body' => $response->body()]);

            return ResultadoExtracao::falha('Resposta inválida do modelo.');
        }

        /** @var array{consumo_kwh?: int, mes_referencia?: ?string, confianca?: float, falhou?: bool, motivo_falha?: ?string}|null $dados */
        $dados = json_decode($texto, true);
        if (! is_array($dados)) {
            Log::warning('Gemini retornou JSON inválido', ['texto' => $texto]);

            return ResultadoExtracao::falha('Resposta inválida do modelo.');
        }

        if ($dados['falhou'] ?? false) {
            Log::info('Gemini não conseguiu extrair consumo', $dados);

            return ResultadoExtracao::falha(
                $dados['motivo_falha'] ?? 'Não foi possível ler a conta. Digite manualmente.'
            );
        }

        return ResultadoExtracao::ok(
            consumoKwh: (int) ($dados['consumo_kwh'] ?? 0),
            mesReferencia: $dados['mes_referencia'] ?? null,
            confianca: (float) ($dados['confianca'] ?? 0.0),
        );
    }

    private function detectarMime(string $caminho): string
    {
        $mime = @mime_content_type($caminho);

        return is_string($mime) && str_starts_with($mime, 'image/') ? $mime : 'image/jpeg';
    }

    /**
     * @return array<string, mixed>
     */
    private function corpoRequisicao(string $mime, string $base64): array
    {
        return [
            'contents' => [[
                'parts' => [
                    ['inline_data' => ['mime_type' => $mime, 'data' => $base64]],
                    ['text' => $this->prompt()],
                ],
            ]],
            'generationConfig' => [
                'temperature' => 0.0,
                'responseMimeType' => 'application/json',
                'responseSchema' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'consumo_kwh' => ['type' => 'INTEGER'],
                        'mes_referencia' => ['type' => 'STRING', 'nullable' => true],
                        'confianca' => ['type' => 'NUMBER'],
                        'falhou' => ['type' => 'BOOLEAN'],
                        'motivo_falha' => ['type' => 'STRING', 'nullable' => true],
                    ],
                    'required' => ['consumo_kwh', 'confianca', 'falhou'],
                ],
            ],
        ];
    }

    private function prompt(): string
    {
        return <<<'PROMPT'
            Esta imagem é uma conta de energia elétrica brasileira (qualquer distribuidora: Energisa, Cemig, Enel, CPFL, Coelba, Light, Equatorial, Celpe, etc.).

            Sua tarefa: extrair o CONSUMO TOTAL em kWh do MÊS DE FATURAMENTO ATUAL.

            Regras obrigatórias:
            1. Se a conta divide o consumo em FAIXAS TARIFÁRIAS (ex: "até 50 kWh", "51-100 kWh", "101-220 kWh", "acima de 220 kWh"), SOME todas as faixas para obter o consumo total.
            2. Use apenas o mês de faturamento atual, NUNCA o histórico de meses anteriores.
            3. Se o valor estiver em MWh, converta para kWh multiplicando por 1000.
            4. Ignore "consumo médio", "previsão", "projeção", "estimativa" e o histórico gráfico.
            5. Se a imagem não é uma conta de luz ou está ilegível, retorne falhou=true.

            Responda SOMENTE no JSON estruturado. Sem explicação extra.
            PROMPT;
    }
}
