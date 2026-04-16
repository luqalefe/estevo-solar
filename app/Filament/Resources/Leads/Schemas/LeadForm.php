<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('telefone')
                    ->tel()
                    ->required(),
                TextInput::make('consumo_kwh')
                    ->required()
                    ->numeric(),
                TextInput::make('potencia_kwp')
                    ->required()
                    ->numeric(),
                TextInput::make('num_placas')
                    ->required()
                    ->numeric(),
                TextInput::make('custo_minimo')
                    ->required()
                    ->numeric(),
                TextInput::make('custo_maximo')
                    ->required()
                    ->numeric(),
                TextInput::make('economia_mensal')
                    ->required()
                    ->numeric(),
                TextInput::make('payback_anos')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('consentimento_lgpd_em')
                    ->required(),
                DateTimePicker::make('whatsapp_clicado_em'),
            ]);
    }
}
