<?php

use App\Http\Controllers\Api\V1\AllocationController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BuyerController;
use App\Http\Controllers\Api\V1\DocumentController;
use App\Http\Controllers\Api\V1\FactoryController;
use App\Http\Controllers\Api\V1\InspectionController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductionController;
use App\Http\Controllers\Api\V1\SampleController;
use App\Http\Controllers\Api\V1\ShipmentController;
use App\Http\Controllers\Api\V1\StyleController;
use App\Http\Controllers\Api\V1\WorkOrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/register', [AuthController::class, 'register']);

    Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);

        Route::middleware('module:buyers')->group(function () {
            Route::apiResource('buyers', BuyerController::class)->except(['create', 'edit']);
        });

        Route::middleware('module:factories')->group(function () {
            Route::apiResource('factories', FactoryController::class)->except(['create', 'edit']);
        });

        Route::middleware('module:product_catalog')->group(function () {
            Route::apiResource('styles', StyleController::class)->except(['create', 'edit', 'destroy']);
        });

        Route::middleware('module:buyer_orders')->group(function () {
            Route::apiResource('orders', OrderController::class)->except(['create', 'edit']);
            Route::post('orders/{order}/approve', [OrderController::class, 'approve']);
            Route::post('orders/{order}/confirm', [OrderController::class, 'confirm']);
            Route::post('orders/{order}/cancel', [OrderController::class, 'cancel']);
        });

        Route::middleware('module:factory_allocations')->group(function () {
            Route::get('orders/{order}/allocations', [AllocationController::class, 'index']);
            Route::post('orders/{order}/allocations', [AllocationController::class, 'store']);
            Route::post('allocations/{allocation}/approve', [AllocationController::class, 'approve']);

            Route::get('work-orders', [WorkOrderController::class, 'index']);
            Route::get('work-orders/{work_order}', [WorkOrderController::class, 'show']);
            Route::post('work-orders/{work_order}/acknowledge', [WorkOrderController::class, 'acknowledge']);
            Route::post('work-orders/{work_order}/accept', [WorkOrderController::class, 'accept']);
            Route::post('work-orders/{work_order}/reject', [WorkOrderController::class, 'reject']);
        });

        Route::middleware('module:samples')->group(function () {
            Route::get('samples', [SampleController::class, 'index']);
            Route::get('samples/{sample}', [SampleController::class, 'show']);
            Route::post('samples/{sample}/transition', [SampleController::class, 'transition']);
        });

        Route::middleware('module:production')->group(function () {
            Route::get('production-updates', [ProductionController::class, 'index']);
            Route::post('production-updates', [ProductionController::class, 'store']);
        });

        Route::middleware('module:quality')->group(function () {
            Route::get('inspections', [InspectionController::class, 'index']);
            Route::post('inspections', [InspectionController::class, 'store']);
        });

        Route::middleware('module:shipments')->group(function () {
            Route::get('shipments', [ShipmentController::class, 'index']);
            Route::post('shipments', [ShipmentController::class, 'store']);
            Route::get('shipments/{shipment}', [ShipmentController::class, 'show']);
        });

        Route::middleware('module:documents')->group(function () {
            Route::get('documents', [DocumentController::class, 'index']);
            Route::get('documents/purchase-orders/{order}/pdf', [DocumentController::class, 'purchaseOrderPdf']);
        });
    });
});
