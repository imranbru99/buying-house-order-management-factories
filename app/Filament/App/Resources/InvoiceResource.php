<?php

namespace App\Filament\App\Resources;

use App\Models\Invoice;
use App\Filament\Concerns\InteractsWithTenantForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = Invoice::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'Finance';

    public static function form(Form $form): Form
    {
        return $form->schema([...self::tenantHiddenField(),
            Forms\Components\TextInput::make('number')->required(),
            Forms\Components\TextInput::make('status'),
            Forms\Components\TextInput::make('total_amount')->numeric(),
            Forms\Components\TextInput::make('currency')->maxLength(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('number'),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('total_amount'),
            Tables\Columns\TextColumn::make('currency'),
        ])->actions([Tables\Actions\EditAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => InvoiceResource\Pages\ListInvoices::route('/'),
            'create' => InvoiceResource\Pages\CreateInvoice::route('/create'),
            'edit' => InvoiceResource\Pages\EditInvoice::route('/{record}/edit'),];
    }
}
