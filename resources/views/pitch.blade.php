<x-layouts.app :title="'Estevo Tech — Sites e CRM para empresas de energia solar'">
    @php
        $whatsapp = config('solar.empresa.whatsapp', '5568992582319');
        $msgComercial = 'Olá! Tenho interesse no site institucional + CRM da Estevo Tech para minha empresa de energia solar.';
        $waUrl = 'https://wa.me/'.$whatsapp.'?text='.urlencode($msgComercial);
    @endphp

    {{-- =================== NAVBAR =================== --}}
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/80 backdrop-blur">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
            <a href="/" class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3v2m0 14v2m9-9h-2M5 12H3m14.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m12.728 0l-1.414-1.414M7.05 7.05L5.636 5.636M16 12a4 4 0 11-8 0 4 4 0 018 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
                </span>
                <span class="text-lg font-semibold text-slate-900">Estevo<span class="text-amber-600">Tech</span></span>
            </a>
            <div class="hidden items-center gap-6 text-sm font-medium text-slate-600 sm:flex">
                <a href="#como-funciona" class="hover:text-slate-900">Como funciona</a>
                <a href="#features" class="hover:text-slate-900">Recursos</a>
                <a href="#faq" class="hover:text-slate-900">FAQ</a>
                <a href="/demo" class="hover:text-slate-900">Demo</a>
            </div>
            <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                class="rounded-full bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600">
                Falar com vendas
            </a>
        </nav>
    </header>

    {{-- =================== HERO =================== --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-amber-50/60 to-white"></div>
        <div class="relative mx-auto max-w-6xl px-4 py-14 sm:py-20">
            <div class="grid items-center gap-10 lg:grid-cols-2">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">
                        ☀ Especialistas em energia solar
                    </span>
                    <h1 class="mt-5 text-3xl font-bold leading-tight tracking-tight text-slate-900 sm:text-5xl">
                        Site institucional + calculadora + CRM
                        <span class="block text-amber-600">num produto só pra sua empresa solar</span>
                    </h1>
                    <p class="mt-5 text-base text-slate-600 sm:text-lg">
                        Seus clientes tiram foto da conta de luz, veem o orçamento pronto com
                        <strong>seus preços</strong> e chegam no seu WhatsApp já com os números.
                        Você recebe o lead quentinho no painel e fecha mais rápido.
                    </p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="/demo"
                            class="flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-6 py-3.5 text-base font-semibold text-white shadow-lg shadow-slate-900/10 transition hover:bg-slate-800">
                            Ver demo ao vivo
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                            class="flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-6 py-3.5 text-base font-semibold text-slate-900 transition hover:bg-slate-50">
                            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 018.413 3.488 11.824 11.824 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.87 9.87 0 001.599 5.391l.6.952-1.002 3.648 3.742-.982.55.292z"/></svg>
                            Quero para minha empresa
                        </a>
                    </div>
                    <p class="mt-4 text-xs text-slate-500">
                        No ar em 3 dias · Domínio próprio · SSL grátis · Sem mensalidade escondida
                    </p>
                </div>

                {{-- Mockup --}}
                <div class="relative mx-auto w-full max-w-md">
                    <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-br from-amber-200 via-amber-100 to-emerald-100 blur-2xl opacity-70"></div>
                    <div class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
                        <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50 px-4 py-2.5">
                            <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                            <span class="ml-3 text-xs text-slate-500">seucliente.com.br</span>
                        </div>
                        <div class="p-5 space-y-4">
                            <p class="text-[10px] font-medium uppercase tracking-wider text-amber-600">Seu orçamento personalizado</p>
                            <h3 class="text-xl font-bold text-slate-900">Olá, Lucas!</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="rounded-xl border border-slate-200 p-3">
                                    <p class="text-[9px] uppercase text-slate-500">Placas</p>
                                    <p class="text-2xl font-bold text-slate-900">6</p>
                                </div>
                                <div class="rounded-xl border border-slate-200 p-3">
                                    <p class="text-[9px] uppercase text-slate-500">Potência</p>
                                    <p class="text-2xl font-bold text-slate-900">2,78</p>
                                </div>
                            </div>
                            <div class="rounded-xl border border-amber-200 bg-amber-50 p-3">
                                <p class="text-[9px] uppercase text-amber-700">Investimento</p>
                                <p class="text-lg font-bold text-amber-900">R$ 12.000 a R$ 14.666</p>
                            </div>
                            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-3">
                                <p class="text-[9px] uppercase text-emerald-700">Economia mensal</p>
                                <p class="text-lg font-bold text-emerald-900">R$ 276,00</p>
                            </div>
                            <a href="/demo" class="flex items-center justify-center gap-2 rounded-xl bg-[#25D366] py-3 text-sm font-semibold text-white transition hover:bg-[#1ebe5a]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 018.413 3.488 11.824 11.824 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.87 9.87 0 001.599 5.391l.6.952-1.002 3.648 3.742-.982.55.292z"/></svg>
                                Testar agora →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =================== PROBLEMAS =================== --}}
    <section class="border-y border-slate-100 bg-slate-50/50">
        <div class="mx-auto max-w-6xl px-4 py-14 sm:py-20">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-2xl font-bold text-slate-900 sm:text-3xl">
                    Você perde vendas todo dia e nem sabe
                </h2>
                <p class="mt-4 text-slate-600">
                    Sites genéricos de calculadora solar tratam seu cliente como qualquer um.
                    Seu vendedor recebe lead cru e gasta 20 minutos recalculando o que o cliente já deveria ter visto.
                </p>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['🕵', 'Cliente chega sem saber o consumo', 'Liga perguntando "quanto custa?" sem ter kWh em mão — ciclo longo, sem sair do lugar.'],
                    ['📊', 'Calculadoras genéricas usam preços nacionais', 'Você prometeu um preço no anúncio. Site mostrou outro. Cliente desconfia e some.'],
                    ['📝', 'Lead vira planilha no WhatsApp', 'Sem CRM, orçamento velho volta de 3 meses depois sem histórico — você perde o fechamento.'],
                    ['⏰', 'Fora do horário comercial = lead perdido', 'Cliente pesquisa às 22h, seu site só captura nome e telefone — quando liga no dia seguinte, já fechou com outro.'],
                ] as [$emoji, $titulo, $texto])
                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="text-2xl">{{ $emoji }}</div>
                        <h3 class="mt-3 font-semibold text-slate-900">{{ $titulo }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $texto }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =================== COMO FUNCIONA =================== --}}
    <section id="como-funciona" class="mx-auto max-w-6xl px-4 py-14 sm:py-20">
        <div class="mx-auto max-w-2xl text-center">
            <p class="text-sm font-medium uppercase tracking-wider text-amber-600">Como funciona</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-900 sm:text-3xl">
                3 passos do primeiro clique ao fechamento
            </h2>
        </div>

        <ol class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach ([
                ['1', 'Cliente tira foto da conta', 'Nossa IA lê automaticamente o consumo em kWh de qualquer distribuidora brasileira — Energisa, Enel, Cemig, CPFL, todas.'],
                ['2', 'Sistema calcula com os SEUS preços', 'Orçamento na hora com suas margens, tarifa local (Acre: R$ 0,92/kWh), placas e kWp estimados.'],
                ['3', 'Lead chega no seu CRM + WhatsApp', 'Mensagem pré-pronta com todos os números. Cliente chega no WhatsApp pronto para fechar — você só precisa atender.'],
            ] as [$num, $titulo, $texto])
                <li class="relative rounded-2xl border border-slate-200 bg-white p-6">
                    <span class="absolute -top-3 left-6 flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-amber-600 text-sm font-bold text-white shadow-lg shadow-amber-500/30">
                        {{ $num }}
                    </span>
                    <h3 class="mt-3 font-bold text-slate-900">{{ $titulo }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $texto }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    {{-- =================== FEATURES GRID =================== --}}
    <section id="features" class="border-t border-slate-100 bg-slate-50/50">
        <div class="mx-auto max-w-6xl px-4 py-14 sm:py-20">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm font-medium uppercase tracking-wider text-amber-600">Recursos</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900 sm:text-3xl">
                    Tudo que sua empresa solar precisa, em um lugar
                </h2>
            </div>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['Leitura automática da conta', 'IA extrai o consumo em kWh mesmo em contas com tabela tarifária em faixas (0–50, 51–100, 101–220 kWh).'],
                    ['Calculadora com seus preços', 'HSP, eficiência, tarifa Energisa, preço por kWp e margem — tudo configurável pelo painel.'],
                    ['WhatsApp pré-preenchido', 'Cliente clica e o WhatsApp abre com nome, consumo, placas, investimento, economia e payback já formatados.'],
                    ['CRM embutido', 'Lista de leads com filtros (clicaram no WhatsApp, não clicaram), busca por nome/email, histórico e data.'],
                    ['Domínio próprio + SSL', 'solar.suaempresa.com.br com HTTPS automático (Let\'s Encrypt). Nada de subdomínio genérico.'],
                    ['LGPD nativo', 'Consentimento registrado por lead, política de privacidade, dados criptografados no servidor.'],
                ] as [$titulo, $texto])
                    <div class="rounded-2xl border border-slate-200 bg-white p-6">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="mt-4 font-semibold text-slate-900">{{ $titulo }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $texto }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =================== DEMO CTA =================== --}}
    <section class="mx-auto max-w-6xl px-4 py-14 sm:py-20">
        <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-900 to-amber-900/40 p-8 sm:p-12">
            <div class="grid items-center gap-8 lg:grid-cols-2">
                <div>
                    <p class="text-sm font-medium uppercase tracking-wider text-amber-400">Teste ao vivo</p>
                    <h2 class="mt-3 text-2xl font-bold text-white sm:text-3xl">
                        Veja como seus clientes vão usar
                    </h2>
                    <p class="mt-4 text-slate-300">
                        Suba uma foto de uma conta de luz (pode ser a sua) e veja a calculadora funcionando
                        de verdade — mesmo fluxo que seus clientes terão no seu site.
                    </p>
                </div>
                <div class="flex justify-center lg:justify-end">
                    <a href="/demo"
                        class="group flex items-center gap-3 rounded-xl bg-amber-500 px-8 py-4 text-base font-semibold text-white shadow-xl shadow-amber-500/40 transition hover:bg-amber-400">
                        Abrir a demo
                        <svg class="h-5 w-5 transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- =================== FAQ =================== --}}
    <section id="faq" class="border-t border-slate-100 bg-slate-50/50">
        <div class="mx-auto max-w-3xl px-4 py-14 sm:py-20">
            <div class="text-center">
                <p class="text-sm font-medium uppercase tracking-wider text-amber-600">FAQ</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900 sm:text-3xl">Perguntas frequentes</h2>
            </div>

            <div class="mt-10 space-y-4">
                @foreach ([
                    ['Em quanto tempo meu site fica no ar?', 'Em média 3 dias úteis após o cadastro. Nós configuramos o domínio, a marca, os seus preços e ajustamos a tarifa local. Você só precisa aprovar e começar a receber leads.'],
                    ['Posso ajustar os preços e as margens?', 'Sim, tudo pelo painel admin. HSP, eficiência do sistema, tarifa da distribuidora, potência da placa, preço por kWp e margem de variação — todos configuráveis.'],
                    ['Funciona em qualquer estado, ou só no Acre?', 'Qualquer estado. Os parâmetros padrão são do Acre (HSP 4,5 e tarifa Energisa), mas ajustamos no setup pro seu estado/distribuidora.'],
                    ['E se a foto da conta estiver ruim e a IA não conseguir ler?', 'Mostra uma mensagem amigável e o cliente digita o consumo manualmente. Mesmo fluxo continua — cálculo + WhatsApp pré-pronto.'],
                    ['Vocês personalizam com minha marca?', 'Sim. Logo, cores primárias, textos da landing e mensagem do WhatsApp tudo ajustado pra sua empresa na entrega.'],
                ] as [$pergunta, $resposta])
                    <details class="group rounded-2xl border border-slate-200 bg-white p-5 open:shadow-sm">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 font-semibold text-slate-900">
                            <span>{{ $pergunta }}</span>
                            <svg class="h-5 w-5 shrink-0 text-slate-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </summary>
                        <p class="mt-3 text-sm text-slate-600">{{ $resposta }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =================== CTA FINAL =================== --}}
    <section class="mx-auto max-w-4xl px-4 py-14 sm:py-20">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-xl shadow-slate-900/5 sm:p-12">
            <h2 class="text-2xl font-bold text-slate-900 sm:text-3xl">
                Pronto pra parar de perder lead?
            </h2>
            <p class="mx-auto mt-4 max-w-xl text-slate-600">
                Manda uma mensagem no WhatsApp que a gente te mostra uma demo personalizada
                da sua empresa e responde todas as dúvidas.
            </p>
            <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                    class="flex items-center justify-center gap-2 rounded-xl bg-[#25D366] px-8 py-4 text-base font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-[#1ebe5a]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 018.413 3.488 11.824 11.824 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.87 9.87 0 001.599 5.391l.6.952-1.002 3.648 3.742-.982.55.292z"/></svg>
                    Falar com a Estevo Tech
                </a>
                <a href="/demo" class="text-sm font-medium text-slate-600 underline hover:text-slate-900">
                    Antes testar a demo
                </a>
            </div>
        </div>
    </section>

    {{-- =================== FOOTER =================== --}}
    <footer class="border-t border-slate-200 bg-slate-50">
        <div class="mx-auto flex max-w-6xl flex-col items-start justify-between gap-4 px-4 py-8 text-sm text-slate-600 sm:flex-row sm:items-center">
            <div>
                <p class="font-semibold text-slate-900">Estevo<span class="text-amber-600">Tech</span></p>
                <p class="mt-1 text-xs">Rio Branco, AC · Especialistas em energia solar digital</p>
            </div>
            <div class="flex gap-5">
                <a href="/demo" class="hover:text-slate-900">Demo</a>
                <a href="#faq" class="hover:text-slate-900">FAQ</a>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="hover:text-slate-900">WhatsApp</a>
            </div>
        </div>
    </footer>
</x-layouts.app>
