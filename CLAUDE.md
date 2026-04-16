# lp-solar

Produto da **Estevo Tech**. Landing page com calculadora de orçamento solar para empresas de energia solar no Acre, vendida em modelo white-label — cada empresa cliente recebe uma instância configurada com sua marca, preços e WhatsApp.

**Diferencial:** calculadora retorna orçamento real (preço da empresa) na hora e abre WhatsApp com os números já prontos, em vez de apenas capturar lead para contato posterior.

---

## Stack

- **Backend:** Laravel 13 + PHP 8.4
- **Frontend:** Blade + Livewire 3 (sem build step complexo; adequado ao escopo)
- **Banco:** PostgreSQL 16
- **Cache/Fila:** Redis 7
- **Testes:** Pest (sobre PHPUnit)
- **Infra dev/prod:** Docker + Docker Compose
- **Proxy/SSL:** Caddy (HTTPS automático via Let's Encrypt)
- **CI/CD:** GitHub Actions → deploy via SSH na VPS

Escolhas feitas com YAGNI em mente: nada de Vue/React, Inertia, Kubernetes, microserviços. O produto é um CRUD com cálculo e redirecionamento para WhatsApp — mantenha simples.

---

## Metodologia: TDD + XP

### TDD é obrigatório

Todo código de produção nasce de um teste que falha. Ciclo estrito:

1. **Red** — escreva o teste mínimo que falha pelo motivo certo.
2. **Green** — escreva o código mínimo que faz o teste passar (pode ser feio).
3. **Refactor** — limpe com a rede de testes verde. Só refatore quando verde.

**Regras:**
- Nenhum PR sobe sem cobertura dos caminhos críticos (cálculo, captura de lead, geração da mensagem do WhatsApp, admin).
- Testes testam **comportamento observável**, não implementação. Não mocke o que você possui — use a classe real.
- Feature tests (HTTP) para fluxos do usuário; Unit tests apenas para regras de domínio isoladas (ex: `SolarCalculoService`).
- Banco de testes é real (Postgres em container), não SQLite in-memory. Divergência entre test e prod já nos mordeu em outros projetos.
- Nome do teste descreve o comportamento: `it('calcula_numero_de_placas_arredondando_pra_cima')`, não `testCalculate()`.

### XP — as práticas que adotamos

- **Pair programming:** Claude e usuário operam como par. Claude escreve, usuário revisa/redireciona. Antes de qualquer implementação não-trivial, alinhe o plano em texto.
- **Small releases:** entregue em fatias verticais. Primeira release pode ser "calculadora com valores hardcoded + botão WhatsApp". Painel admin vem depois.
- **Simple design:** 4 regras do Kent Beck, em ordem:
  1. Passa nos testes
  2. Revela intenção (nomes claros)
  3. Sem duplicação
  4. Menor número de elementos
- **Refactoring contínuo:** toda vez que tocar um arquivo, deixe-o um pouco melhor. Mas só com testes verdes.
- **Continuous integration:** push para `main` sempre que testes passarem. Branches longas não existem neste projeto. Se precisar de feature flag, use flag — não branch.
- **Collective ownership:** nenhum arquivo "pertence" a alguém. Qualquer um refatora qualquer coisa.
- **Sustainable pace:** não empilhe features. Entregue, valide com empresa piloto, ajuste.

### O que NÃO fazer

- Não adicione camadas especulativas (Repository, UseCase, DTO) sem necessidade concreta. Eloquent + Service é suficiente até provar o contrário.
- Não teste getters/setters, migrations, ou framework.
- Não crie abstrações para "futura flexibilidade". Três linhas duplicadas são melhores que abstração prematura.

---

## Docker

Um único `docker-compose.yml` serve dev. Prod usa `docker-compose.prod.yml` com overrides.

**Serviços:**
- `app` — PHP-FPM 8.3 com Laravel
- `web` — Caddy servindo estáticos e fazendo proxy para `app`
- `db` — PostgreSQL 16
- `redis` — Redis 7
- `queue` — worker da fila (mesmo image de `app`, comando diferente)

**Dev:**
```bash
docker compose up -d
docker compose exec app php artisan migrate --seed
docker compose exec app ./vendor/bin/pest
```

**Prod:**
- Imagens buildadas no CI, versionadas por commit SHA.
- `.env.production` vive na VPS, nunca no repo.
- Migrations rodam no deploy (`php artisan migrate --force`).

---

## Infraestrutura: VPS simples

Alvo: **Hetzner CX22** (~€4/mês) ou equivalente. Ubuntu 24.04 LTS. Uma VPS roda N instâncias do produto (uma por empresa cliente) sob subdomínios.

**Setup mínimo:**
- Docker + Docker Compose
- Caddy como proxy reverso (um container global, não por instância)
- Cada cliente = um `docker-compose.yml` em `/opt/clientes/{slug}/` com seu próprio subdomínio e `.env`
- Backup diário do Postgres para storage externo (B2/R2)

**Deploy:**
- `git push` → GitHub Actions roda testes → se verde, SSH na VPS e `docker compose pull && docker compose up -d`
- Sem Kubernetes, sem Swarm, sem Ansible. Shell script e Compose dão conta do volume que precisamos.

---

## Domínio do produto

### Cálculo solar (região Acre)

Parâmetros default (ajustáveis por cliente no painel admin):

| Parâmetro | Default |
|---|---|
| HSP (Horas de Sol Pleno) | 4,50 h/dia |
| Eficiência do sistema | 0,80 |
| Tarifa Energisa AC | R$ 0,92/kWh |
| Potência por placa | 550 W |
| Preço médio por kWp | R$ 4.800,00 |
| Margem de variação | ±10% |

### Serviço central

`App\Services\SolarCalculoService` expõe:
- `calcular(int $consumoKwh): ResultadoCalculo` — puro, sem I/O, 100% testável
- `gerarMensagemWhatsapp(ResultadoCalculo $r, string $nome): string`

Valores de entrada (HSP, tarifa, etc.) vêm de `config/solar.php` no MVP e migram para tabela `parametros` quando houver multi-tenant.

### Fluxo do usuário

1. Usuário entra na landing, digita consumo em kWh
2. Livewire calcula em tempo real (ou no submit), mostra resultado
3. Usuário clica "Falar com consultor" → abre WhatsApp com mensagem pré-preenchida
4. Lead fica registrado em `leads` (nome, consumo, resultado, timestamp)

---

## Público-alvo do serviço

**Cliente final (B2B):** empresas de energia solar do Acre (Sisa Solar, Solar Energia Acre, Mega Solar AC, etc.). Oferta é: "sua landing page com calculadora que já abre WhatsApp pronto — única no mercado local."

**Usuário do produto (B2C):** consumidor acreano pesquisando energia solar para sua casa/comércio. Perfil: pouco técnico, quer saber quanto custa e quanto economiza.

Copywriting, UX e SEO devem servir o usuário B2C. Painel admin e relatórios servem o cliente B2B.

---

## Convenções deste repo

- **Commits:** mensagem em português, imperativo, curta. Referencie o teste que guiou a mudança quando aplicável.
- **PRs:** devem ter descrição curta do "porquê", não do "o quê" (o diff mostra o quê).
- **Nomenclatura:** domínio em português (`Lead`, `Orcamento`, `Parametro`), infraestrutura em inglês (`Controller`, `Service`, `Middleware`). Não misture dentro da mesma classe.
- **Migrations:** nunca edite uma migration após push para `main`. Crie nova.
- **Seeders:** devem rodar em prod sem quebrar nada. Use `firstOrCreate`.

---

## Referências do projeto

- Análise de mercado completa: ver contexto compartilhado pelo usuário em conversas anteriores (empresas concorrentes, dados demográficos do Acre, parâmetros técnicos regionais).
- Tarifa Energisa AC deve ser verificada a cada 6 meses.
