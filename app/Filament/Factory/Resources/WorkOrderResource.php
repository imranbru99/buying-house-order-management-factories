<?php

namespace App\Filament\Factory\Resources;

use App\Filament\Concerns\InteractsWithTenantForms;
use App\Filament\Factory\Resources\WorkOrderResource\Pages;
use App\Models\FactoryWorkOrder;
use App\Services\WorkOrders\FactoryWorkOrderService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use InvalidArgumentException;

class WorkOrderResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = FactoryWorkOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Work orders';

    public static function form(Form $form): Form
    {
        return $form->schema([
            ...self::tenantHiddenField(),
            Forms\Components\TextInput::make('number')->disabled(),
            Forms\Components\TextInput::make('status')->disabled(),
            Forms\Components\TextInput::make('quantity')->disabled(),
            Forms\Components\DatePicker::make('delivery_date')->disabled(),
            Forms\Components\Textarea::make('factory_comments')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('delivery_date')->date(),
                Tables\Columns\TextColumn::make('acknowledged_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\Action::make('acknowledge')
                    ->icon('heroicon-o-bell-alert')
                    ->visible(fn (FactoryWorkOrder $record): bool => $record->acknowledged_at === null)
                    ->action(function (FactoryWorkOrder $record, FactoryWorkOrderService $service): void {
                        try {
                            $service->acknowledge($record);
                            Notification::make()->title('Work order acknowledged')->success()->send();
                        } catch (InvalidArgumentException $exception) {
                            Notification::make()->title($exception->getMessage())->danger()->send();
                        }
                    }),
                Tables\Actions\Action::make('accept')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(fn (FactoryWorkOrder $record): bool => $record->accepted_at === null && $record->rejected_at === null)
                    ->requiresConfirmation()
                    ->action(function (FactoryWorkOrder $record, FactoryWorkOrderService $service): void {
                        try {
                            $service->accept($record);
                            Notification::make()->title('Work order accepted')->success()->send();
                        } catch (InvalidArgumentException $exception) {
                            Notification::make()->title($exception->getMessage())->danger()->send();
                        }
                    }),
                Tables\Actions\Action::make('reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->visible(fn (FactoryWorkOrder $record): bool => $record->rejected_at === null && $record->accepted_at === null)
                    ->form([
                        Forms\Components\Textarea::make('factory_comments')->required(),
                    ])
                    ->action(function (FactoryWorkOrder $record, array $data, FactoryWorkOrderService $service): void {
                        try {
                            $service->reject($record, $data['factory_comments']);
                            Notification::make()->title('Work order rejected')->warning()->send();
                        } catch (InvalidArgumentException $exception) {
                            Notification::make()->title($exception->getMessage())->danger()->send();
                        }
                    }),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkOrders::route('/'),
            'edit' => Pages\EditWorkOrder::route('/{record}/edit'),
        ];
    }
}
