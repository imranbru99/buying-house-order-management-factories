<?php

namespace App\Filament\App\Resources;

use App\Models\Task;
use App\Filament\Concerns\InteractsWithTenantForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TaskResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = Task::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Workspace';

    public static function form(Form $form): Form
    {
        return $form->schema([...self::tenantHiddenField(),
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\TextInput::make('status'),
            Forms\Components\TextInput::make('assigned_to_user_id')->numeric(),
            Forms\Components\DatePicker::make('due_date'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title'),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('due_date')->date(),
        ])->actions([Tables\Actions\EditAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => TaskResource\Pages\ListTasks::route('/'),
            'create' => TaskResource\Pages\CreateTask::route('/create'),
            'edit' => TaskResource\Pages\EditTask::route('/{record}/edit'),];
    }
}
