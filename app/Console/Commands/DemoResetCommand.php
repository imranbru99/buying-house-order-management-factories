<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DemoResetCommand extends Command
{
    protected $signature = 'tradeflow:demo-reset {--force : Skip confirmation}';

    protected $description = 'Drop all tables and re-seed the TradeFlow demo dataset (local/demo only)';

    public function handle(): int
    {
        if (! in_array(config('app.env'), ['local', 'demo'], true)) {
            $this->error('tradeflow:demo-reset is only allowed when APP_ENV is local or demo.');

            return self::FAILURE;
        }

        if (! $this->option('force') && ! $this->confirm('This will erase all data. Continue?', false)) {
            $this->warn('Aborted.');

            return self::SUCCESS;
        }

        $this->call('migrate:fresh', ['--seed' => true, '--force' => true]);

        $this->info('Demo database has been reset and seeded.');

        return self::SUCCESS;
    }
}
