<?php

use App\Filament\Resources\Leads\LeadResource;
use App\Filament\Resources\Leads\Pages\ListLeads;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('redireciona visitante do /admin para login', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
});

it('autenticado acessa o dashboard do admin', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk();
});

it('lista leads ordenados por mais recente primeiro', function () {
    $user = User::factory()->create();
    $antigo = Lead::factory()->create(['nome' => 'Cliente Antigo', 'created_at' => now()->subDays(5)]);
    $novo = Lead::factory()->create(['nome' => 'Cliente Novo', 'created_at' => now()]);

    Livewire::actingAs($user)
        ->test(ListLeads::class)
        ->assertCanSeeTableRecords([$novo, $antigo])
        ->assertCanRenderTableColumn('nome')
        ->assertCanRenderTableColumn('economia_mensal')
        ->assertCanRenderTableColumn('num_placas');
});

it('filtro "clicaram no WhatsApp" mostra apenas quem clicou', function () {
    $user = User::factory()->create();
    $clicou = Lead::factory()->create(['whatsapp_clicado_em' => now()]);
    $naoClicou = Lead::factory()->create(['whatsapp_clicado_em' => null]);

    Livewire::actingAs($user)
        ->test(ListLeads::class)
        ->filterTable('clicou_whatsapp')
        ->assertCanSeeTableRecords([$clicou])
        ->assertCanNotSeeTableRecords([$naoClicou]);
});
