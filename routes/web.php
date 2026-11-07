<?php

use App\Http\Controllers\Website\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebsiteController::class, 'home'])->name('website.home');
Route::get('/about', [WebsiteController::class, 'about']);
Route::get('/platform', [WebsiteController::class, 'platform']);
Route::get('/features', [WebsiteController::class, 'features']);
Route::get('/modules', [WebsiteController::class, 'modules']);
Route::get('/modules/{key}', [WebsiteController::class, 'moduleShow']);
Route::get('/pricing', [WebsiteController::class, 'pricing']);
Route::get('/security', [WebsiteController::class, 'security']);
Route::get('/integrations', [WebsiteController::class, 'integrations']);
Route::get('/faq', [WebsiteController::class, 'faq']);
Route::get('/contact', [WebsiteController::class, 'contact']);
Route::get('/request-demo', [WebsiteController::class, 'requestDemo']);
Route::get('/blog', [WebsiteController::class, 'blog']);
Route::get('/documentation', [WebsiteController::class, 'documentation']);
Route::get('/demo-access', [WebsiteController::class, 'demoAccess'])->name('website.demo-access');

$featurePages = [
    'buying-house-management',
    'buyer-management',
    'factory-management',
    'order-management',
    'factory-allocation',
    'merchandising-management',
    'costing-management',
    'sample-management',
    'production-monitoring',
    'tna-management',
    'quality-management',
    'compliance-management',
    'shipment-management',
    'commercial-documentation',
    'lc-management',
    'finance-management',
    'ai-business-assistant',
    'buyer-portal',
    'factory-portal',
    'supplier-portal',
];

foreach ($featurePages as $page) {
    Route::get('/'.$page, [WebsiteController::class, 'featurePage'])->defaults('slug', $page);
}

Route::get('/robots.txt', [WebsiteController::class, 'robots']);
Route::get('/sitemap.xml', [WebsiteController::class, 'sitemap']);
