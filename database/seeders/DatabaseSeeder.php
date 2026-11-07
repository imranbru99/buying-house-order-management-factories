<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $demoEnabled = config('tradeflow.demo.enabled')
            || in_array(config('app.env'), ['local', 'demo'], true);

        if ($demoEnabled) {
            $this->call(DemoSeeder::class);
        }
    }
}
