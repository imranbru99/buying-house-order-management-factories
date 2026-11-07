<?php

namespace App\Filament\App\Resources;

use App\Filament\Concerns\InteractsWithTenantForms;
use App\Models\Inspection;
use App\Services\Documents\DocumentPdfService;
use App\Services\Quality\InspectionService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InspectionResource extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string $model = Inspection::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationGroup = 'Quality';

    public static function form(Form $form): Form
    {
        return $form->schema([
            ...self::tenantHiddenField(),
            Forms\Components\TextInput::make('number')->required(),
            Forms\Components\TextInput::make('factory_work_order_id')->numeric(),
            Forms\Components\TextInput::make('type'),
            Forms\Components\TextInput::make('status')
                ->default('scheduled')
                ->disabled()
                ->dehydrated(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('number'),
            Tables\Columns\TextColumn::make('type'),
            Tables\Columns\TextColumn::make('status')->badge(),
        ])->actions([
            Tables\Actions\Action::make('recordResult')
                ->label('Record result')
                ->icon('heroicon-o-clipboard-document-check')
                ->visible(fn (Inspection $record): bool => ! in_array($record->status, [
                    InspectionService::RESULT_PASS,
                    InspectionService::RESULT_FAIL,
                ], true))
                ->form([
                    Forms\Components\Repeater::make('defects')
                        ->schema([
                            Forms\Components\TextInput::make('defect_code')->required(),
                            Forms\Components\TextInput::make('quantity')->numeric()->required(),
                            Forms\Components\Select::make('severity')
                                ->options([
                                    'minor' => 'Minor',
                                    'major' => 'Major',
                                    'critical' => 'Critical',
                                ])
                                ->default('minor')
                                ->required(),
                        ])
                        ->defaultItems(0)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('critical_limit')
                        ->numeric()
                        ->default(0)
                        ->required(),
                ])
                ->action(function (Inspection $record, array $data, InspectionService $service): void {
                    $service->record(
                        $record,
                        $data['defects'] ?? [],
                        isset($data['critical_limit']) ? (int) $data['critical_limit'] : 0,
                    );

                    Notification::make()->title('Inspection result recorded')->success()->send();
                }),
            Tables\Actions\Action::make('downloadPdf')
                ->label('Download PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function (Inspection $record, DocumentPdfService $pdfs) {
                    $binary = $pdfs->inspectionPdf($record);

                    return response()->streamDownload(
                        fn () => print($binary),
                        'inspection-'.$record->number.'.pdf',
                        ['Content-Type' => 'application/pdf'],
                    );
                }),
            Tables\Actions\EditAction::make(),
        ])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => InspectionResource\Pages\ListInspections::route('/'),
            'create' => InspectionResource\Pages\CreateInspection::route('/create'),
            'edit' => InspectionResource\Pages\EditInspection::route('/{record}/edit'),
        ];
    }
}
