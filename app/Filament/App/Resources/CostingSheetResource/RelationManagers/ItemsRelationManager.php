<?php

namespace App\Filament\App\Resources\CostingSheetResource\RelationManagers;

use App\Filament\Concerns\InteractsWithTenantForms;
use App\Models\CostingItem;
use App\Models\CostingSheet;
use App\Services\Costing\CostingCalculator;
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
            Forms\Components\TextInput::make('category'),
            Forms\Components\TextInput::make('description'),
            Forms\Components\TextInput::make('quantity')->numeric(),
            Forms\Components\TextInput::make('unit_cost')->numeric(),
            Forms\Components\TextInput::make('line_total')
                ->numeric()
                ->disabled()
                ->dehydrated(false),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('unit_cost'),
                Tables\Columns\TextColumn::make('line_total'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (CostingItem $record, CostingCalculator $calculator): void {
                        $this->recalculateSheet($calculator);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function (CostingItem $record, CostingCalculator $calculator): void {
                        $this->recalculateSheet($calculator);
                    }),
                Tables\Actions\DeleteAction::make()
                    ->after(function (CostingCalculator $calculator): void {
                        $this->recalculateSheet($calculator);
                    }),
            ]);
    }

    protected function recalculateSheet(CostingCalculator $calculator): void
    {
        /** @var CostingSheet $sheet */
        $sheet = $this->getOwnerRecord();
        $calculator->recalculate($sheet);
    }
}
