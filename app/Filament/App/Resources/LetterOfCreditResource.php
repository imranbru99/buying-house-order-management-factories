<?php

namespace App\Filament\App\Resources;

use App\Models\LetterOfCredit;
use App\Filament\Concerns\InteractsWithTenantForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LetterOfCreditResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = LetterOfCredit::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Finance';

    public static function form(Form $form): Form
    {
        return $form->schema([...self::tenantHiddenField(),
            Forms\Components\Select::make('buyer_id')->relationship('buyer', 'name')->searchable(),
            Forms\Components\TextInput::make('number')->required(),
            Forms\Components\TextInput::make('amount')->numeric(),
            Forms\Components\TextInput::make('lc_value')->numeric(),
            Forms\Components\TextInput::make('currency')->maxLength(3),
            Forms\Components\TextInput::make('status'),
            Forms\Components\DatePicker::make('expiry_date'),
            Forms\Components\DatePicker::make('latest_shipment_date'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('number'),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('amount'),
            Tables\Columns\TextColumn::make('expiry_date')->date(),
        ])->actions([Tables\Actions\EditAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => LetterOfCreditResource\Pages\ListLetterOfCredits::route('/'),
            'create' => LetterOfCreditResource\Pages\CreateLetterOfCredit::route('/create'),
            'edit' => LetterOfCreditResource\Pages\EditLetterOfCredit::route('/{record}/edit'),];
    }
}
