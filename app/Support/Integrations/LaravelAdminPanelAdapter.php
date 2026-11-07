<?php

namespace App\Support\Integrations;

/**
 * Compatibility adapter for imrandevbd/laravel-admin-panel.
 *
 * TradeFlow uses Filament v3 as the primary administration framework
 * (/platform, /app, /buyer, /factory, /supplier). laravel-admin-panel is a
 * competing full admin stack (own UI, RBAC, media, audit). Installing both
 * as active admin systems would duplicate navigation, auth guards, and
 * asset pipelines.
 *
 * This adapter intentionally does NOT boot laravel-admin-panel. It documents
 * how complementary capabilities could be bridged later if a future stable
 * release exposes headless APIs without registering a competing UI.
 *
 * Decision (2026-11): package not installed. See docs/PACKAGE_COMPATIBILITY.md.
 */
final class LaravelAdminPanelAdapter
{
    public static function isInstalled(): bool
    {
        return class_exists(\ImranDevBD\LaravelAdminPanel\LaravelAdminPanelServiceProvider::class);
    }

    /**
     * @return array{status: string, reason: string, primary_admin: string}
     */
    public static function status(): array
    {
        return [
            'status' => self::isInstalled() ? 'installed_but_disabled' : 'not_installed',
            'reason' => 'Filament remains the exclusive administration UI for TradeFlow panels.',
            'primary_admin' => 'filament/filament ^3.3',
        ];
    }
}
