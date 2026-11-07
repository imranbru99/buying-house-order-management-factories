<?php

namespace App\Filament\App\Resources;

use App\Filament\Concerns\InteractsWithTenantForms;
use App\Models\Factory;
use App\Models\Sample;
use App\Services\Samples\SampleWorkflowService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use InvalidArgumentException;

class SampleResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = Sample::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'Pre-Order';

    /** @var array<string, list<string>> */
    protected static array $transitions = [
        'requested' => ['assigned'],
        'assigned' => ['in_development'],
        'in_development' => ['submitted'],
        'submitted' => ['buyer_review'],
        'approved' => ['completed'],
        'rejected' => ['in_development'],
    ];

    public static function form(Form $form): Form
    {
        return $form->schema([
            ...self::tenantHiddenField(),
            Forms\Components\Select::make('style_id')->relationship('style', 'name')->searchable()->preload(),
            Forms\Components\Select::make('buyer_id')->relationship('buyer', 'name')->searchable()->preload(),
            Forms\Components\Select::make('factory_id')->relationship('factory', 'name')->searchable()->preload(),
            Forms\Components\TextInput::make('number')->required(),
            Forms\Components\TextInput::make('sample_type'),
            Forms\Components\TextInput::make('status')
                ->default('requested')
                ->disabled()
                ->dehydrated(),
            Forms\Components\DatePicker::make('due_date'),
            Forms\Components\TextInput::make('quantity')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('number'),
            Tables\Columns\TextColumn::make('sample_type'),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('due_date')->date(),
        ])->actions([
            self::transitionAction('assigned', 'Assign', 'heroicon-o-building-office-2', 'primary', [
                Forms\Components\Select::make('factory_id')
                    ->label('Factory')
                    ->options(fn () => Factory::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
            ]),
            self::transitionAction('in_development', 'Start development', 'heroicon-o-play', 'warning'),
            self::transitionAction('submitted', 'Submit', 'heroicon-o-paper-airplane', 'info'),
            self::transitionAction('buyer_review', 'Send to buyer', 'heroicon-o-user-group', 'primary'),
            self::transitionAction('completed', 'Complete', 'heroicon-o-check-badge', 'success'),
            Tables\Actions\EditAction::make(),
        ])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    /**
     * @param  array<int, Forms\Components\Component>  $formSchema
     */
    protected static function transitionAction(
        string $toStatus,
        string $label,
        string $icon,
        string $color,
        array $formSchema = [],
    ): Tables\Actions\Action {
        return Tables\Actions\Action::make('transition_'.$toStatus)
            ->label($label)
            ->icon($icon)
            ->color($color)
            ->visible(fn (Sample $record): bool => in_array($toStatus, self::$transitions[$record->status] ?? [], true))
            ->form($formSchema)
            ->requiresConfirmation(fn (): bool => $formSchema === [])
            ->action(function (Sample $record, array $data, SampleWorkflowService $service) use ($toStatus): void {
                try {
                    $service->transition($record, $toStatus, $data);
                    Notification::make()->title("Sample moved to {$toStatus}")->success()->send();
                } catch (InvalidArgumentException $exception) {
                    Notification::make()->title($exception->getMessage())->danger()->send();
                }
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => SampleResource\Pages\ListSamples::route('/'),
            'create' => SampleResource\Pages\CreateSample::route('/create'),
            'edit' => SampleResource\Pages\EditSample::route('/{record}/edit'),
        ];
    }
}
