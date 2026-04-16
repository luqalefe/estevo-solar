<?php

declare(strict_types=1);

namespace App\Filament\Resources\Leads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('nome')
                    ->label('Cliente')
                    ->searchable()
                    ->weight('semibold'),
                TextColumn::make('telefone')
                    ->label('WhatsApp')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->copyable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('consumo_kwh')
                    ->label('Consumo')
                    ->suffix(' kWh')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('num_placas')
                    ->label('Placas')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('economia_mensal')
                    ->label('Economia/mês')
                    ->money('BRL')
                    ->sortable()
                    ->weight('semibold')
                    ->color('success'),
                TextColumn::make('custo_maximo')
                    ->label('Investimento')
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('whatsapp_clicado_em')
                    ->label('WhatsApp')
                    ->boolean()
                    ->tooltip(fn ($record) => $record->whatsapp_clicado_em?->format('d/m/Y H:i')),
                TextColumn::make('created_at')
                    ->label('Recebido')
                    ->since()
                    ->sortable()
                    ->tooltip(fn ($record) => $record->created_at->format('d/m/Y H:i')),
            ])
            ->filters([
                Filter::make('clicou_whatsapp')
                    ->label('Clicaram no WhatsApp')
                    ->query(fn ($query) => $query->whereNotNull('whatsapp_clicado_em')),
                Filter::make('nao_clicaram')
                    ->label('Ainda não clicaram')
                    ->query(fn ($query) => $query->whereNull('whatsapp_clicado_em')),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
