<?php

namespace App\Filament\Buyer\Resources;

use App\Filament\Concerns\InteractsWithTenantForms;
use App\Models\Sample;
use App\Services\Samples\SampleWorkflowService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use InvalidArgumentException;

class SampleApprovalResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = Sample::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'Samples';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            ...self::tenantHiddenField(),
            Forms\Components\TextInput::make('number')->disabled(),
            Forms\Components\TextInput::make('status')->disabled(),
            Forms\Components\DatePicker::make('due_date')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('number'),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('due_date')->date(),
        ])->actions([
            Tables\Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (Sample $record): bool => $record->status === 'buyer_review')
                ->requiresConfirmation()
                ->action(function (Sample $record, SampleWorkflowService $service): void {
                    try {
                        $service->transition($record, 'approved');
                        Notification::make()->title('Sample approved')->success()->send();
                    } catch (InvalidArgumentException $exception) {
                        Notification::make()->title($exception->getMessage())->danger()->send();
                    }
                }),
            Tables\Actions\Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (Sample $record): bool => $record->status === 'buyer_review')
                ->requiresConfirmation()
                ->action(function (Sample $record, SampleWorkflowService $service): void {
                    try {
                        $service->transition($record, 'rejected');
                        Notification::make()->title('Sample rejected')->warning()->send();
                    } catch (InvalidArgumentException $exception) {
                        Notification::make()->title($exception->getMessage())->danger()->send();
                    }
                }),
            Tables\Actions\ViewAction::make(),
        ])->bulkActions([[]]);
    }

    public static function getPages(): array
    {
        return [
            'index' => SampleApprovalResource\Pages\ListSamples::route('/'),
            'view' => SampleApprovalResource\Pages\ViewSample::route('/{record}'),
        ];
    }
}
