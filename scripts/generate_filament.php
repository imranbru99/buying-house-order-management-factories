<?php

declare(strict_types=1);

$base = dirname(__DIR__);

function wf(string $path, string $contents): void
{
    $dir = dirname($path);
    is_dir($dir) || mkdir($dir, 0755, true);
    file_put_contents($path, $contents);
}

function model_short(string $modelFqn): string
{
    $p = strrpos($modelFqn, '\\');

    return $p === false ? $modelFqn : substr($modelFqn, $p + 1);
}

function mk_pages(string $panel, string $res, string $modelShort, bool $ro = false): void
{
    global $base;
    $ns = "App\\Filament\\{$panel}\\Resources\\{$res}\\Pages";
    $rel = "{$base}/app/Filament/{$panel}/Resources/{$res}/Pages";

    wf("{$rel}/List{$modelShort}s.php", <<<PHP
<?php

namespace {$ns};

use App\\Filament\\{$panel}\\Resources\\{$res};
use Filament\\Actions;
use Filament\\Resources\\Pages\\ListRecords;

class List{$modelShort}s extends ListRecords
{
    protected static string \$resource = {$res}::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\\CreateAction::make(),
        ];
    }
}

PHP);

    if ($ro) {
        wf("{$rel}/View{$modelShort}.php", <<<PHP
<?php

namespace {$ns};

use App\\Filament\\{$panel}\\Resources\\{$res};
use Filament\\Resources\\Pages\\ViewRecord;

class View{$modelShort} extends ViewRecord
{
    protected static string \$resource = {$res}::class;
}

PHP);

        return;
    }

    wf("{$rel}/Create{$modelShort}.php", <<<PHP
<?php

namespace {$ns};

use App\\Filament\\{$panel}\\Resources\\{$res};
use Filament\\Resources\\Pages\\CreateRecord;

class Create{$modelShort} extends CreateRecord
{
    protected static string \$resource = {$res}::class;
}

PHP);

    wf("{$rel}/Edit{$modelShort}.php", <<<PHP
<?php

namespace {$ns};

use App\\Filament\\{$panel}\\Resources\\{$res};
use Filament\\Actions;
use Filament\\Resources\\Pages\\EditRecord;

class Edit{$modelShort} extends EditRecord
{
    protected static string \$resource = {$res}::class;

    protected function getHeaderActions(): array
    {
        return [Actions\\DeleteAction::make()];
    }
}

PHP);
}

function mk_resource(string $panel, string $resClass, string $modelFqn, string $group, string $icon, string $form, string $table, bool $ro = false): void
{
    global $base;
    $modelShort = model_short($modelFqn);
    $ns = "App\\Filament\\{$panel}\\Resources";
    $path = "{$base}/app/Filament/{$panel}/Resources/{$resClass}.php";

    $actions = $ro
        ? "Tables\\Actions\\ViewAction::make()"
        : "Tables\\Actions\\EditAction::make()";

    $bulk = $ro ? '[]' : "Tables\\Actions\\BulkActionGroup::make([Tables\\Actions\\DeleteBulkAction::make()])";

    $pages = $ro
        ? "'index' => {$resClass}\\Pages\\List{$modelShort}s::route('/'),\n            'view' => {$resClass}\\Pages\\View{$modelShort}::route('/{record}'),"
        : "'index' => {$resClass}\\Pages\\List{$modelShort}s::route('/'),\n            'create' => {$resClass}\\Pages\\Create{$modelShort}::route('/create'),\n            'edit' => {$resClass}\\Pages\\Edit{$modelShort}::route('/{record}/edit'),";

    $roMethods = $ro ? "
    public static function canCreate(): bool { return false; }
    public static function canEdit(\Illuminate\\Database\\Eloquent\\Model \$record): bool { return false; }
    public static function canDelete(\Illuminate\\Database\\Eloquent\\Model \$record): bool { return false; }
" : '';

    $headerList = $ro ? '' : '';

    wf($path, <<<PHP
<?php

namespace {$ns};

use {$modelFqn};
use App\\Filament\\Concerns\\InteractsWithTenantForms;
use Filament\\Forms;
use Filament\\Forms\\Form;
use Filament\\Resources\\Resource;
use Filament\\Tables;
use Filament\\Tables\\Table;

class {$resClass} extends Resource
{
    use InteractsWithTenantForms;

    protected static ?string \$model = {$modelShort}::class;
    protected static ?string \$navigationIcon = '{$icon}';
    protected static ?string \$navigationGroup = '{$group}';
{$roMethods}
    public static function form(Form \$form): Form
    {
        return \$form->schema([...self::tenantHiddenField(),
{$form}
        ]);
    }

    public static function table(Table \$table): Table
    {
        return \$table->columns([
{$table}
        ])->actions([{$actions}])->bulkActions([{$bulk}]);
    }

    public static function getPages(): array
    {
        return [{$pages}];
    }
}

PHP);

    mk_pages($panel, $resClass, $modelShort, $ro);
}

$t = "Forms\\Components\\TextInput::make";
$d = "Forms\\Components\\DatePicker::make";
$dt = "Forms\\Components\\DateTimePicker::make";
$s = "Forms\\Components\\Select::make";
$c = "Tables\\Columns\\TextColumn::make";

// App master data
mk_resource('App', 'BuyerResource', 'App\\Models\\Buyer', 'Master Data', 'heroicon-o-building-storefront',
    "            {$t}('code')->required(),\n            {$t}('name')->required(),\n            {$t}('brand_name'),\n            {$t}('country'),\n            {$t}('payment_terms'),\n            {$t}('credit_limit')->numeric(),\n            {$t}('currency')->maxLength(3),\n            {$t}('status'),",
    "            {$c}('code')->searchable(),\n            {$c}('name')->searchable(),\n            {$c}('country'),\n            {$c}('status')->badge(),",
);

mk_resource('App', 'FactoryResource', 'App\\Models\\Factory', 'Master Data', 'heroicon-o-building-office-2',
    "            {$t}('code')->required(),\n            {$t}('name')->required(),\n            {$t}('country'),\n            {$t}('monthly_capacity')->numeric(),\n            {$t}('available_capacity')->numeric(),\n            {$t}('approval_status'),\n            {$t}('quality_score')->numeric(),\n            {$t}('delivery_score')->numeric(),\n            {$t}('status'),",
    "            {$c}('code')->searchable(),\n            {$c}('name')->searchable(),\n            {$c}('country'),\n            {$c}('status')->badge(),",
);

mk_resource('App', 'SupplierResource', 'App\\Models\\Supplier', 'Master Data', 'heroicon-o-truck',
    "            {$t}('code')->required(),\n            {$t}('name')->required(),\n            {$t}('country'),\n            {$t}('status'),",
    "            {$c}('code'),\n            {$c}('name'),\n            {$c}('status')->badge(),",
);

mk_resource('App', 'StyleResource', 'App\\Models\\Style', 'Product', 'heroicon-o-squares-2x2',
    "            {$s}('buyer_id')->relationship('buyer', 'name')->searchable()->preload(),\n            {$t}('style_number'),\n            {$t}('code'),\n            {$t}('buyer_style_ref'),\n            {$t}('name')->required(),\n            Forms\\Components\\Textarea::make('description')->columnSpanFull(),\n            {$t}('season'),\n            {$t}('status'),",
    "            {$c}('style_number'),\n            {$c}('name'),\n            {$c}('season'),\n            {$c}('status')->badge(),",
);

mk_resource('App', 'InquiryResource', 'App\\Models\\Inquiry', 'Pre-Order', 'heroicon-o-chat-bubble-left-right',
    "            {$s}('buyer_id')->relationship('buyer', 'name')->searchable()->preload(),\n            {$t}('number')->required(),\n            {$t}('status'),\n            {$d}('inquiry_date'),",
    "            {$c}('number'),\n            {$c}('status')->badge(),\n            {$c}('inquiry_date')->date(),",
);

mk_resource('App', 'SampleResource', 'App\\Models\\Sample', 'Pre-Order', 'heroicon-o-beaker',
    "            {$s}('style_id')->relationship('style', 'name')->searchable()->preload(),\n            {$s}('buyer_id')->relationship('buyer', 'name')->searchable()->preload(),\n            {$s}('factory_id')->relationship('factory', 'name')->searchable()->preload(),\n            {$t}('number')->required(),\n            {$t}('sample_type'),\n            {$t}('status'),\n            {$d}('due_date'),\n            {$t}('quantity')->numeric(),",
    "            {$c}('number'),\n            {$c}('sample_type'),\n            {$c}('status')->badge(),\n            {$c}('due_date')->date(),",
);

mk_resource('App', 'CostingSheetResource', 'App\\Models\\CostingSheet', 'Pre-Order', 'heroicon-o-calculator',
    "            {$t}('number')->required(),\n            {$s}('style_id')->relationship('style', 'name')->searchable()->preload(),\n            {$t}('version'),\n            {$t}('status'),\n            {$t}('total_cost')->numeric(),\n            {$t}('currency')->maxLength(3),\n            {$t}('profit_margin')->numeric(),\n            {$t}('commission')->numeric(),\n            {$t}('costing_type'),\n            {$dt}('approved_at'),",
    "            {$c}('number'),\n            {$c}('status')->badge(),\n            {$c}('total_cost'),\n            {$c}('currency'),",
);

mk_resource('App', 'FactoryAllocationResource', 'App\\Models\\FactoryAllocation', 'Production', 'heroicon-o-arrows-right-left',
    "            {$s}('buyer_purchase_order_id')->relationship('purchaseOrder', 'number')->searchable()->preload(),\n            {$s}('factory_id')->relationship('factory', 'name')->searchable()->preload(),\n            {$s}('style_id')->relationship('style', 'name')->searchable()->preload(),\n            {$t}('allocated_quantity')->numeric(),\n            {$d}('delivery_date'),\n            {$t}('status'),\n            {$dt}('approved_at'),",
    "            {$c}('purchaseOrder.number')->label('PO'),\n            {$c}('factory.name'),\n            {$c}('allocated_quantity'),\n            {$c}('status')->badge(),",
);

mk_resource('App', 'FactoryWorkOrderResource', 'App\\Models\\FactoryWorkOrder', 'Production', 'heroicon-o-wrench-screwdriver',
    "            {$t}('number')->required(),\n            {$s}('factory_id')->relationship('factory', 'name')->searchable(),\n            {$t}('status'),\n            {$t}('quantity')->numeric(),\n            {$d}('delivery_date'),\n            Forms\\Components\\Textarea::make('factory_comments')->columnSpanFull(),",
    "            {$c}('number'),\n            {$c}('factory.name'),\n            {$c}('status')->badge(),\n            {$c}('quantity'),\n            {$c}('delivery_date')->date(),",
);

mk_resource('App', 'TnaMilestoneResource', 'App\\Models\\TnaMilestone', 'Production', 'heroicon-o-flag',
    "            {$t}('code')->required(),\n            {$t}('name')->required(),\n            {$t}('offset_days')->numeric(),\n            {$t}('sort_order')->numeric(),",
    "            {$c}('code'),\n            {$c}('name'),\n            {$c}('offset_days'),\n            {$c}('sort_order'),",
);

mk_resource('App', 'ProductionUpdateResource', 'App\\Models\\ProductionUpdate', 'Production', 'heroicon-o-chart-bar',
    "            {$t}('production_plan_id')->numeric(),\n            {$t}('completed_quantity')->numeric(),\n            {$dt}('reported_at'),\n            Forms\\Components\\Textarea::make('notes')->columnSpanFull(),",
    "            {$c}('production_plan_id'),\n            {$c}('completed_quantity'),\n            {$c}('reported_at')->dateTime(),",
);

mk_resource('App', 'InspectionResource', 'App\\Models\\Inspection', 'Quality', 'heroicon-o-magnifying-glass',
    "            {$t}('number')->required(),\n            {$t}('factory_work_order_id')->numeric(),\n            {$t}('type'),\n            {$t}('status'),",
    "            {$c}('number'),\n            {$c}('type'),\n            {$c}('status')->badge(),",
);

mk_resource('App', 'ShipmentResource', 'App\\Models\\Shipment', 'Logistics', 'heroicon-o-globe-alt',
    "            {$s}('buyer_purchase_order_id')->relationship('purchaseOrder', 'number')->searchable(),\n            {$t}('number')->required(),\n            {$t}('status'),\n            {$t}('incoterm'),\n            {$t}('shipping_method'),\n            {$d}('etd'),\n            {$d}('eta'),\n            {$t}('bol_number'),",
    "            {$c}('number'),\n            {$c}('status')->badge(),\n            {$c}('etd')->date(),\n            {$c}('eta')->date(),",
);

mk_resource('App', 'LetterOfCreditResource', 'App\\Models\\LetterOfCredit', 'Finance', 'heroicon-o-document-text',
    "            {$s}('buyer_id')->relationship('buyer', 'name')->searchable(),\n            {$t}('number')->required(),\n            {$t}('amount')->numeric(),\n            {$t}('lc_value')->numeric(),\n            {$t}('currency')->maxLength(3),\n            {$t}('status'),\n            {$d}('expiry_date'),\n            {$d}('latest_shipment_date'),",
    "            {$c}('number'),\n            {$c}('status')->badge(),\n            {$c}('amount'),\n            {$c}('expiry_date')->date(),",
);

mk_resource('App', 'InvoiceResource', 'App\\Models\\Invoice', 'Finance', 'heroicon-o-receipt-percent',
    "            {$t}('number')->required(),\n            {$t}('status'),\n            {$t}('total_amount')->numeric(),\n            {$t}('currency')->maxLength(3),",
    "            {$c}('number'),\n            {$c}('status')->badge(),\n            {$c}('total_amount'),\n            {$c}('currency'),",
);

mk_resource('App', 'TaskResource', 'App\\Models\\Task', 'Workspace', 'heroicon-o-clipboard-document-list',
    "            {$t}('title')->required(),\n            {$t}('status'),\n            {$t}('assigned_to_user_id')->numeric(),\n            {$d}('due_date'),",
    "            {$c}('title'),\n            {$c}('status')->badge(),\n            {$c}('due_date')->date(),",
);

mk_resource('App', 'DocumentResource', 'App\\Models\\Document', 'Workspace', 'heroicon-o-folder',
    "            {$t}('title')->required(),\n            {$t}('disk'),\n            {$t}('path'),\n            {$t}('mime_type'),\n            {$t}('size')->numeric(),",
    "            {$c}('title'),\n            {$c}('mime_type'),\n            {$c}('size'),",
);

// Platform (no tenant hidden)
wf("{$base}/app/Filament/Platform/Resources/TenantResource.php", <<<'PHP'
<?php

namespace App\Filament\Platform\Resources;

use App\Filament\Platform\Resources\TenantResource\Pages;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('code')->required(),
            Forms\Components\TextInput::make('slug')->required(),
            Forms\Components\TextInput::make('status'),
            Forms\Components\TextInput::make('timezone'),
            Forms\Components\TextInput::make('default_currency')->maxLength(3),
            Forms\Components\TextInput::make('default_locale'),
            Forms\Components\DateTimePicker::make('trial_ends_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('code'),
            Tables\Columns\TextColumn::make('slug'),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('trial_ends_at')->dateTime(),
        ])->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
PHP);

mk_pages('Platform', 'TenantResource', 'Tenant');

wf("{$base}/app/Filament/Platform/Resources/PlanResource.php", <<<'PHP'
<?php

namespace App\Filament\Platform\Resources;

use App\Filament\Platform\Resources\PlanResource\Pages;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('code')->required(),
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\Textarea::make('description')->columnSpanFull(),
            Forms\Components\TextInput::make('price')->numeric(),
            Forms\Components\TextInput::make('currency')->maxLength(3),
            Forms\Components\TextInput::make('billing_interval'),
            Forms\Components\Toggle::make('is_active'),
            Forms\Components\TextInput::make('sort_order')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('code'),
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('price'),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
        ])->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
PHP);

mk_pages('Platform', 'PlanResource', 'Plan');

// Buyer portal read-only
mk_resource('Buyer', 'PurchaseOrderResource', 'App\\Models\\BuyerPurchaseOrder', 'Orders', 'heroicon-o-shopping-bag',
    "            {$t}('number'),\n            {$t}('status'),\n            {$d}('order_date'),\n            {$t}('total_amount')->numeric(),",
    "            {$c}('number'),\n            {$c}('status')->badge(),\n            {$c}('order_date')->date(),\n            {$c}('total_amount'),",
    ro: true,
);

mk_resource('Buyer', 'SampleApprovalResource', 'App\\Models\\Sample', 'Samples', 'heroicon-o-beaker',
    "            {$t}('number'),\n            {$t}('status'),\n            {$d}('due_date'),",
    "            {$c}('number'),\n            {$c}('status')->badge(),\n            {$c}('due_date')->date(),",
    ro: true,
);

mk_resource('Buyer', 'ShipmentResource', 'App\\Models\\Shipment', 'Logistics', 'heroicon-o-truck',
    "            {$t}('number'),\n            {$t}('status'),\n            {$d}('eta'),",
    "            {$c}('number'),\n            {$c}('status')->badge(),\n            {$c}('eta')->date(),",
    ro: true,
);

mk_resource('Supplier', 'SupplierPurchaseOrderResource', 'App\\Models\\SupplierPurchaseOrder', 'Orders', 'heroicon-o-document',
    "            {$t}('number'),\n            {$t}('status'),\n            {$t}('total_amount')->numeric(),",
    "            {$c}('number'),\n            {$c}('status')->badge(),\n            {$c}('total_amount'),",
    ro: true,
);

// Factory portal
mk_resource('Factory', 'ProductionUpdateResource', 'App\\Models\\ProductionUpdate', 'Production', 'heroicon-o-chart-bar',
    "            {$t}('completed_quantity')->numeric(),\n            {$dt}('reported_at'),\n            Forms\\Components\\Textarea::make('notes')->columnSpanFull(),",
    "            {$c}('completed_quantity'),\n            {$c}('reported_at')->dateTime(),",
);

mk_resource('Factory', 'InspectionResource', 'App\\Models\\Inspection', 'Quality', 'heroicon-o-magnifying-glass',
    "            {$t}('number'),\n            {$t}('type'),\n            {$t}('status'),",
    "            {$c}('number'),\n            {$c}('type'),\n            {$c}('status')->badge(),",
);

echo "Generated standard resources.\n";
