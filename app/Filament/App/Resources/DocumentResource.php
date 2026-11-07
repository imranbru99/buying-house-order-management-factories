<?php

namespace App\Filament\App\Resources;

use App\Models\Document;
use App\Filament\Concerns\InteractsWithTenantForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = Document::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Workspace';

    public static function form(Form $form): Form
    {
        return $form->schema([...self::tenantHiddenField(),
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\TextInput::make('disk'),
            Forms\Components\TextInput::make('path'),
            Forms\Components\TextInput::make('mime_type'),
            Forms\Components\TextInput::make('size')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title'),
            Tables\Columns\TextColumn::make('mime_type'),
            Tables\Columns\TextColumn::make('size'),
        ])->actions([Tables\Actions\EditAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => DocumentResource\Pages\ListDocuments::route('/'),
            'create' => DocumentResource\Pages\CreateDocument::route('/create'),
            'edit' => DocumentResource\Pages\EditDocument::route('/{record}/edit'),];
    }
}
