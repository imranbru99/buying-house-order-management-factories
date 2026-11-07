<?php

return [

    'name' => env('TRADEFLOW_APP_NAME', 'TradeFlow'),

    'tagline' => 'Order management for buying houses',

    'logo_path' => null,

    'support_email' => env('TRADEFLOW_SUPPORT_EMAIL', 'support@tradeflow.test'),

    'demo' => [
        'enabled' => filter_var(
            env('TRADEFLOW_DEMO_ENABLED', env('DEMO_SEED', true)),
            FILTER_VALIDATE_BOOLEAN
        ),
        'default_tenant_slug' => env('TRADEFLOW_DEMO_TENANT', 'global-apparel'),
        'reset_hours' => (int) env('TRADEFLOW_DEMO_RESET_HOURS', 24),
        'show_credentials_on_website' => filter_var(env('TRADEFLOW_DEMO_SHOW_CREDENTIALS', true), FILTER_VALIDATE_BOOLEAN),
    ],

    'tenancy' => [
        'header' => 'X-Tenant',
        'subdomain_enabled' => (bool) env('TRADEFLOW_SUBDOMAIN_TENANCY', true),
        'base_domain' => env('TRADEFLOW_BASE_DOMAIN', 'tradeflow.test'),
    ],

    'currencies' => [
        'USD' => ['name' => 'US Dollar', 'symbol' => '$', 'decimals' => 2],
        'EUR' => ['name' => 'Euro', 'symbol' => '€', 'decimals' => 2],
        'GBP' => ['name' => 'British Pound', 'symbol' => '£', 'decimals' => 2],
        'BDT' => ['name' => 'Bangladeshi Taka', 'symbol' => '৳', 'decimals' => 2],
        'CNY' => ['name' => 'Chinese Yuan', 'symbol' => '¥', 'decimals' => 2],
        'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹', 'decimals' => 2],
    ],

    'default_currency' => env('TRADEFLOW_DEFAULT_CURRENCY', 'USD'),

    'languages' => [
        'en' => 'English',
        'bn' => 'Bengali',
        'zh' => 'Chinese (Simplified)',
    ],

    'default_locale' => env('TRADEFLOW_DEFAULT_LOCALE', 'en'),

    'default_timezone' => env('TRADEFLOW_DEFAULT_TIMEZONE', 'UTC'),

    'portal_types' => [
        'platform',
        'tenant',
        'buyer',
        'factory',
        'supplier',
    ],

];
