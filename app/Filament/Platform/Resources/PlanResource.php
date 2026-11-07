<?php

namespace App\Filament\Platform\Resources;

use App\Filament\Platform\Resources\PlanResource\Pages;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('code')->required(),
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\Textarea::make('description')->columnSpanFull(),
            Forms\Components\TextInput::make('price')->numeric(),
            Forms\Components\TextInput::make('currency')->maxLength(3),
            Forms\Components\TextInput::make('billing_interval'),
            Forms\Components\Toggle::make('is_active'),
            Forms\Components\TextInput::make('sort_order')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('code'),
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('price'),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
        ])->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}