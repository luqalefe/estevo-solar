<div class="min-h-screen">
    @if ($lead)
        {{-- ============ RESULTADO ============ --}}
        <main class="mx-auto max-w-2xl px-4 py-10 sm:py-16">
            <header class="text-center">
                <p class="text-sm font-medium uppercase tracking-wider text-amber-600">
                    Seu orçamento personalizado
                </p>
                <h1 class="mt-3 text-3xl font-bold leading-tight text-slate-900 sm:text-4xl">
                    Olá, <span class="text-amber-600">{{ $lead->nome }}</span>!
                </h1>
                <p class="mt-3 text-base text-slate-600">
                    Calculamos exatamente o que você precisa para zerar sua conta de luz.
                </p>
            </header>

            <section class="mt-10 grid grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Placas solares</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $lead->num_placas }}</p>
                    <p class="mt-1 text-xs text-slate-500">de 550W cada</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Potência</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ number_format((float) $lead->potencia_kwp, 2, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">kWp instalados</p>
                </div>

                <div class="col-span-2 rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                    <p class="text-xs font-medium uppercase tracking-wider text-amber-700">Investimento estimado</p>
                    <p class="mt-2 text-2xl font-bold text-amber-900 sm:text-3xl">
                        R$ {{ number_format((float) $lead->custo_minimo, 2, ',', '.') }}
                        <span class="text-lg font-medium text-amber-700">a</span>
                        R$ {{ number_format((float) $lead->custo_maximo, 2, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs text-amber-700">sistema completo instalado</p>
                </div>

                <div class="col-span-2 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                    <p class="text-xs font-medium uppercase tracking-wider text-emerald-700">Economia mensal</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-900">
                        R$ {{ number_format((float) $lead->economia_mensal, 2, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs text-emerald-700">
                        Retorno do investimento em aproximadamente
                        <strong>{{ (int) round((float) $lead->payback_anos) }} anos</strong> —
                        placas duram 25+
                    </p>
                </div>
            </section>

            <div class="mt-8">
                <a href="https://wa.me/{{ config('solar.empresa.whatsapp') }}?text={{ urlencode($mensagemWhatsapp) }}"
                    target="_blank" rel="noopener"
                    class="flex w-full items-center justify-center gap-3 rounded-2xl bg-[#25D366] px-6 py-4 text-lg font-semibold text-white shadow-lg shadow-emerald-500/20 transition hover:bg-[#1ebe5a] active:scale-[0.99]">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 018.413 3.488 11.824 11.824 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.87 9.87 0 001.599 5.391l.6.952-1.002 3.648 3.742-.982.55.292z"/>
                    </svg>
                    Quero instalar — falar no WhatsApp
                </a>
                <p class="mt-3 text-center text-xs text-slate-500">
                    Seu consultor já tem todos os números — sem recalcular.
                </p>
            </div>
        </main>
    @else
        {{-- ============ FORMULÁRIO ============ --}}
        <main class="mx-auto max-w-xl px-4 py-8 sm:py-16">
            <header class="text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">
                    ☀ Energia solar no Acre
                </span>
                <h1 class="mt-5 text-3xl font-bold leading-tight text-slate-900 sm:text-4xl">
                    Descubra em <span class="text-amber-600">30 segundos</span>
                    quanto custa ter energia solar na sua casa
                </h1>
                <p class="mt-3 text-base text-slate-600 sm:text-lg">
                    Digite o consumo da sua conta de luz e veja o orçamento completo —
                    placas, investimento e economia — na hora.
                </p>
            </header>

            <form wire:submit="calcular" novalidate
                class="mt-8 space-y-5 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                <div class="rounded-2xl border border-dashed border-amber-300 bg-amber-50/50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9a2 2 0 012-2h1.586a1 1 0 00.707-.293l1.414-1.414A1 1 0 019.414 5h5.172a1 1 0 01.707.293l1.414 1.414A1 1 0 0017.414 7H19a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <circle cx="12" cy="13" r="3.5" stroke-width="1.8"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-slate-900">Atalho: envie a foto da sua conta</p>
                            <p class="mt-0.5 text-xs text-slate-600">Preenche o consumo automaticamente — qualquer distribuidora do Brasil.</p>

                            <label class="mt-2 inline-flex cursor-pointer items-center gap-2 rounded-full border border-amber-300 bg-white px-3 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-50">
                                <span wire:loading.remove wire:target="foto, updatedFoto">Escolher imagem</span>
                                <span wire:loading wire:target="foto, updatedFoto">Lendo a conta…</span>
                                <input type="file" wire:model="foto" accept="image/*" capture="environment" class="sr-only"
                                    wire:loading.attr="disabled" wire:target="foto, updatedFoto">
                            </label>

                            @if ($mensagemExtracao)
                                <p class="mt-2 text-xs font-medium {{ $consumoKwh ? 'text-emerald-700' : 'text-red-600' }}">
                                    {{ $mensagemExtracao }}
                                </p>
                            @endif
                            @error('foto') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label for="nome" class="block text-sm font-medium text-slate-700">Seu nome</label>
                    <input type="text" id="nome" wire:model="nome" autocomplete="name"
                        class="mt-1.5 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-base shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    @error('nome') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">E-mail</label>
                    <input type="email" id="email" wire:model="email" autocomplete="email"
                        class="mt-1.5 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-base shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    @error('email') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="telefone" class="block text-sm font-medium text-slate-700">WhatsApp (com DDD)</label>
                    <input type="tel" id="telefone" wire:model="telefone" autocomplete="tel"
                        placeholder="(68) 99999-0000"
                        class="mt-1.5 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-base shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    @error('telefone') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="consumoKwh" class="block text-sm font-medium text-slate-700">
                        Consumo mensal em kWh
                    </label>
                    <input type="number" id="consumoKwh" wire:model="consumoKwh" min="50" max="10000"
                        placeholder="ex: 300"
                        class="mt-1.5 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-base shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    <p class="mt-1.5 text-xs text-slate-500">
                        Encontre esse número na sua conta da Energisa, no campo "Consumo do mês".
                    </p>
                    @error('consumoKwh') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-start gap-3 rounded-xl bg-slate-50 p-3 text-sm text-slate-700">
                    <input type="checkbox" wire:model="consentimentoLgpd"
                        class="mt-0.5 h-5 w-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                    <span>
                        Concordo que meus dados sejam usados para contato sobre este orçamento.
                        <a href="#" class="text-amber-600 hover:underline">Política de privacidade</a>
                    </span>
                </label>
                @error('consentimentoLgpd') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                <button type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-amber-500 px-6 py-4 text-base font-semibold text-white shadow-lg shadow-amber-500/30 transition hover:bg-amber-600 active:scale-[0.99]">
                    Calcular meu orçamento agora
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-slate-500">
                Cálculo baseado em HSP 4,5 h/dia e tarifa Energisa AC.
                Preços aproximados (±10%) — confirmação no orçamento final.
            </p>
        </main>
    @endif
</div>
