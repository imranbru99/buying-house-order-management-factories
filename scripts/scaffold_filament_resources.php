<?php

declare(strict_types=1);

/**
 * One-off scaffold for TradeFlow Filament v3 resources.
 */

$base = dirname(__DIR__);

function write_file(string $path, string $contents): void
{
    $dir = dirname($path);
    if (! is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    file_put_contents($path, $contents);
}

function resource_pages(string $ns, string $resourceClass, string $modelShort, bool $readOnly = false): void
{
    global $base;
    $pagesNs = "{$ns}\\{$resourceClass}\\Pages";
    $dir = str_replace('\\', '/', "{$base}/app/Filament/".str_replace('App\\Filament\\', '', $ns)."/Resources/{$resourceClass}/Pages");

    $list = <<<PHP
<?php

namespace {$pagesNs};

use {$ns}\\{$resourceClass};
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class List{$modelShort}s extends ListRecords
{
    protected static string \$resource = {$resourceClass}::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(! {$resourceClass}::isReadOnly()),
        ];
    }
}

PHP;

    write_file("{$dir}/List{$modelShort}s.php", $list);

    if ($readOnly) {
        $view = <<<PHP
<?php

namespace {$pagesNs};

use {$ns}\\{$resourceClass};
use Filament\Resources\Pages\ViewRecord;

class View{$modelShort} extends ViewRecord
{
    protected static string \$resource = {$resourceClass}::class;
}

PHP;
        write_file("{$dir}/View{$modelShort}.php", $view);

        return;
    }

    $create = <<<PHP
<?php

namespace {$pagesNs};

use {$ns}\\{$resourceClass};
use Filament\Resources\Pages\CreateRecord;

class Create{$modelShort} extends CreateRecord
{
    protected static string \$resource = {$resourceClass}::class;
}

PHP;
    write_file("{$dir}/Create{$modelShort}.php", $create);

    $edit = <<<PHP
<?php

namespace {$pagesNs};

use {$ns}\\{$resourceClass};
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit{$modelShort} extends EditRecord
{
    protected static string \$resource = {$resourceClass}::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

PHP;
    write_file("{$dir}/Edit{$modelShort}.php", $edit);
}

function make_resource(
    string $panelFolder,
    string $resourceClass,
    string $modelClass,
    string $navigationGroup,
    string $navigationIcon,
    string $formBody,
    string $tableBody,
    bool $readOnly = false,
    string $extra = '',
    ?int $navigationSort = null,
): void {
    global $base;
    $ns = "App\\Filament\\{$panelFolder}\\Resources";
    $modelShort = class_basename($modelClass);
    $path = "{$base}/app/Filament/{$panelFolder}/Resources/{$resourceClass}.php";

    $uses = "use App\\Models\\{$modelShort};\nuse App\\Filament\\Concerns\\InteractsWithTenantForms;\nuse Filament\\Forms;\nuse Filament\\Forms\\Form;\nuse Filament\\Resources\\Resource;\nuse Filament\\Tables;\nuse Filament\\Tables\\Table;";

    $pages = $readOnly
        ? "'index' => {$resourceClass}\\Pages\\List{$modelShort}s::route('/'),
            'view' => {$resourceClass}\\Pages\\View{$modelShort}::route('/{record}'),"
        : "'index' => {$resourceClass}\\Pages\\List{$modelShort}s::route('/'),
            'create' => {$resourceClass}\\Pages\\Create{$modelShort}::route('/create'),
            'edit' => {$resourceClass}\\Pages\\Edit{$modelShort}::route('/{record}/edit'),";

    $readOnlyMethods = $readOnly ? "
    public static function isReadOnly(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return false;
    }
" : '';

    $sortLine = $navigationSort !== null ? "\n    protected static ?int \$navigationSort = {$navigationSort};" : '';

    $resource = <<<PHP
<?php

namespace {$ns};

{$uses}
{$extra}

class {$resourceClass} extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string \$model = {$modelShort}::class;

    protected static ?string \$navigationIcon = '{$navigationIcon}';

    protected static ?string \$navigationGroup = '{$navigationGroup}';{$sortLine}
{$readOnlyMethods}
    public static function form(Form \$form): Form
    {
        return \$form
            ->schema([
                ...self::tenantHiddenField(),
{$formBody}
            ]);
    }

    public static function table(Table \$table): Table
    {
        return \$table
            ->columns([
{$tableBody}
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()->visible({$resourceClass}::isReadOnly()),
                Tables\Actions\EditAction::make()->visible(! {$resourceClass}::isReadOnly()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(! {$resourceClass}::isReadOnly()),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            {$pages}
        ];
    }
}

PHP;

    if (! $readOnly) {
        $resource = str_replace("{$resourceClass}::isReadOnly()", 'false', $resource);
        $resource = str_replace('! false', 'true', $resource);
    }

    write_file($path, $resource);
    resource_pages($ns, $resourceClass, $modelShort, $readOnly);
}

// --- App panel resources ---

$text = fn (string $name) => "Forms\\Components\\TextInput::make('{$name}')";
$textarea = fn (string $name) => "Forms\\Components\\Textarea::make('{$name}')->columnSpanFull()";
$select = fn (string $name) => "Forms\\Components\\Select::make('{$name}')";
$date = fn (string $name) => "Forms\\Components\\DatePicker::make('{$name}')";
$datetime = fn (string $name) => "Forms\\Components\\DateTimePicker::make('{$name}')";
$number = fn (string $name) => "Forms\\Components\\TextInput::make('{$name}')->numeric()";
$col = fn (string $name, string $label = '') => "Tables\\Columns\\TextColumn::make('{$name}')" . ($label ? "->label('{$label}')" : '') . '->searchable()->sortable()';

make_resource('App', 'BuyerResource', 'Buyer', 'Master Data', 'heroicon-o-building-storefront',
    implode(",\n                ", [
        "{$text('code')}->required()->maxLength(50)",
        "{$text('name')}->required()->maxLength(255)",
        "{$text('brand_name')}->maxLength(255)",
        "{$text('country')}->maxLength(100)",
        "{$text('payment_terms')}->maxLength(255)",
        "{$number('credit_limit')}",
        "{$text('currency')}->maxLength(3)",
        "{$text('status')}->maxLength(50)",
    ]),
    implode(",\n                ", [
        $col('code'), $col('name'), $col('brand_name'), $col('country'), $col('status'),
    ]),
    navigationSort: 1,
);

make_resource('App', 'FactoryResource', 'Factory', 'Master Data', 'heroicon-o-building-office-2',
    implode(",\n                ", [
        "{$text('code')}->required()",
        "{$text('name')}->required()",
        "{$text('country')}",
        "{$number('monthly_capacity')}",
        "{$number('available_capacity')}",
        "{$text('approval_status')}",
        "{$number('quality_score')}",
        "{$number('delivery_score')}",
        "{$text('status')}",
    ]),
    implode(",\n                ", [
        $col('code'), $col('name'), $col('country'), $col('approval_status'), $col('status'),
    ]),
    navigationSort: 2,
);

make_resource('App', 'SupplierResource', 'Supplier', 'Master Data', 'heroicon-o-truck',
    implode(",\n                ", [
        "{$text('code')}->required()",
        "{$text('name')}->required()",
        "{$text('country')}",
        "{$text('status')}",
    ]),
    implode(",\n                ", [
        $col('code'), $col('name'), $col('country'), $col('status'),
    ]),
    navigationSort: 3,
);

make_resource('App', 'StyleResource', 'Style', 'Product', 'heroicon-o-squares-2x2',
    implode(",\n                ", [
        "{$select('buyer_id')}->relationship('buyer', 'name')->searchable()->preload()",
        "{$text('style_number')}",
        "{$text('code')}",
        "{$text('buyer_style_ref')}",
        "{$text('name')}->required()",
        "{$textarea('description')}",
        "{$text('season')}",
        "{$text('status')}",
    ]),
    implode(",\n                ", [
        $col('style_number'), $col('name'), $col('season'), $col('status'),
    ]),
);

make_resource('App', 'InquiryResource', 'Inquiry', 'Pre-Order', 'heroicon-o-chat-bubble-left-right',
    implode(",\n                ", [
        "{$select('buyer_id')}->relationship('buyer', 'name')->searchable()->preload()",
        "{$text('number')}->required()",
        "{$text('status')}",
        "{$date('inquiry_date')}",
    ]),
    implode(",\n                ", [
        $col('number'), $col('status'), $col('inquiry_date'),
    ]),
);

echo "Scaffold partial 1 done\n";
