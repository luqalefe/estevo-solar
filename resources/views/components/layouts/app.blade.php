<!DOCTYPE html>
<html lang="pt-BR" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('solar.empresa.nome') }}</title>
    <meta name="description" content="Descubra em 30 segundos quanto custa instalar energia solar na sua casa no Acre. Orçamento real na hora.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 font-sans">
    {{ $slot }}
    @livewireScripts
</body>
</html>
