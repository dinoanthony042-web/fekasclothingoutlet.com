<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the admin users page when a user has no created_at timestamp', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $user = new User([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'role' => 'user',
    ]);
    $user->forceFill(['created_at' => null]);
    $user->save();

    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    $response->assertOk();
    $response->assertSee('Test User');
    $response->assertSee('N/A');
});
