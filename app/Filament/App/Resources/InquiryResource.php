<?php

namespace App\Filament\App\Resources;

use App\Models\Inquiry;
use App\Filament\Concerns\InteractsWithTenantForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InquiryResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = Inquiry::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Pre-Order';

    public static function form(Form $form): Form
    {
        return $form->schema([...self::tenantHiddenField(),
            Forms\Components\Select::make('buyer_id')->relationship('buyer', 'name')->searchable()->preload(),
            Forms\Components\TextInput::make('number')->required(),
            Forms\Components\TextInput::make('status'),
            Forms\Components\DatePicker::make('inquiry_date'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('number'),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('inquiry_date')->date(),
        ])->actions([Tables\Actions\EditAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => InquiryResource\Pages\ListInquirys::route('/'),
            'create' => InquiryResource\Pages\CreateInquiry::route('/create'),
            'edit' => InquiryResource\Pages\EditInquiry::route('/{record}/edit'),];
    }
}
