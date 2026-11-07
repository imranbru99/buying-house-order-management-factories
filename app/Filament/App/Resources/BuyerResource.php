<?php

namespace App\Filament\App\Resources;

use App\Models\Buyer;
use App\Filament\Concerns\InteractsWithTenantForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BuyerResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = Buyer::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form->schema([...self::tenantHiddenField(),
            Forms\Components\TextInput::make('code')->required(),
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('brand_name'),
            Forms\Components\TextInput::make('country'),
            Forms\Components\TextInput::make('payment_terms'),
            Forms\Components\TextInput::make('credit_limit')->numeric(),
            Forms\Components\TextInput::make('currency')->maxLength(3),
            Forms\Components\TextInput::make('status'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('code')->searchable(),
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('country'),
            Tables\Columns\TextColumn::make('status')->badge(),
        ])->actions([Tables\Actions\EditAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => BuyerResource\Pages\ListBuyers::route('/'),
            'create' => BuyerResource\Pages\CreateBuyer::route('/create'),
            'edit' => BuyerResource\Pages\EditBuyer::route('/{record}/edit'),];
    }
}
