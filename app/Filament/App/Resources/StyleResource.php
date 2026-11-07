<?php

namespace App\Filament\App\Resources;

use App\Models\Style;
use App\Filament\Concerns\InteractsWithTenantForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StyleResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = Style::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Product';

    public static function form(Form $form): Form
    {
        return $form->schema([...self::tenantHiddenField(),
            Forms\Components\Select::make('buyer_id')->relationship('buyer', 'name')->searchable()->preload(),
            Forms\Components\TextInput::make('style_number'),
            Forms\Components\TextInput::make('code'),
            Forms\Components\TextInput::make('buyer_style_ref'),
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\Textarea::make('description')->columnSpanFull(),
            Forms\Components\TextInput::make('season'),
            Forms\Components\TextInput::make('status'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('style_number'),
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('season'),
            Tables\Columns\TextColumn::make('status')->badge(),
        ])->actions([Tables\Actions\EditAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => StyleResource\Pages\ListStyles::route('/'),
            'create' => StyleResource\Pages\CreateStyle::route('/create'),
            'edit' => StyleResource\Pages\EditStyle::route('/{record}/edit'),];
    }
}
