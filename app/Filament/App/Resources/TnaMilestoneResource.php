<?php

namespace App\Filament\App\Resources;

use App\Models\TnaMilestone;
use App\Filament\Concerns\InteractsWithTenantForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TnaMilestoneResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = TnaMilestone::class;
    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationGroup = 'Production';

    public static function form(Form $form): Form
    {
        return $form->schema([...self::tenantHiddenField(),
            Forms\Components\TextInput::make('code')->required(),
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('offset_days')->numeric(),
            Forms\Components\TextInput::make('sort_order')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('code'),
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('offset_days'),
            Tables\Columns\TextColumn::make('sort_order'),
        ])->actions([Tables\Actions\EditAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => TnaMilestoneResource\Pages\ListTnaMilestones::route('/'),
            'create' => TnaMilestoneResource\Pages\CreateTnaMilestone::route('/create'),
            'edit' => TnaMilestoneResource\Pages\EditTnaMilestone::route('/{record}/edit'),];
    }
}
