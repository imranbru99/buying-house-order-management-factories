<?php

use App\Models\User;
use Database\Seeders\DemoSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(DemoSeeder::class);
});

test('demo owner can authenticate with documented password', function () {
    $user = User::query()->where('email', 'owner@demo.tradeflow.test')->first();

    expect($user)->not->toBeNull()
        ->and(Hash::check(DemoSeeder::DEMO_PASSWORD, $user->password))->toBeTrue();

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'owner@demo.tradeflow.test',
        'password' => DemoSeeder::DEMO_PASSWORD,
    ]);

    $response->assertSuccessful()
        ->assertJsonStructure(['token', 'user']);
});

test('demo access page is available when demo is enabled', function () {
    config(['tradeflow.demo.enabled' => true]);

    $this->get('/demo-access')
        ->assertSuccessful()
        ->assertSee('owner@demo.tradeflow.test');
});
