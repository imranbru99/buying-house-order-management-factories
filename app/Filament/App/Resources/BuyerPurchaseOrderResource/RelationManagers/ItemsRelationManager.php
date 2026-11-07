<?php

namespace App\Filament\App\Resources\BuyerPurchaseOrderResource\RelationManagers;

use App\Filament\Concerns\InteractsWithTenantForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    use InteractsWithTenantForms;

    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form->schema([
            ...self::tenantHiddenField(),
            Forms\Components\Select::make('style_id')->relationship('style', 'name')->searchable()->preload(),
            Forms\Components\TextInput::make('line_no')->numeric(),
            Forms\Components\TextInput::make('quantity')->numeric()->required(),
            Forms\Components\TextInput::make('unit_price')->numeric(),
            Forms\Components\TextInput::make('line_total')->numeric(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('line_no')
            ->columns([
                Tables\Columns\TextColumn::make('line_no'),
                Tables\Columns\TextColumn::make('style.name'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('unit_price'),
                Tables\Columns\TextColumn::make('line_total'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
