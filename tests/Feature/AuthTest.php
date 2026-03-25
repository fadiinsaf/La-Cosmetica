<?php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('user can register and receive token', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Mehdi',
        'email' => 'mehdi@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'user',
            'token',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'mehdi@example.com',
        'role' => 'client',
    ]);
});

test('user can login with correct credentials', function () {
    User::create([
        'name' => 'Mehdi',
        'email' => 'mehdi@example.com',
        'password' => Hash::make('password123'),
        'role' => 'client',
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'mehdi@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'user',
            'token',
        ]);
});

test('user can logout', function () {
    User::create([
        'name' => 'Mehdi',
        'email' => 'mehdi@example.com',
        'password' => Hash::make('password123'),
        'role' => 'client',
    ]);

    $loginResponse = $this->postJson('/api/login', [
        'email' => 'mehdi@example.com',
        'password' => 'password123',
    ]);

    $token = $loginResponse->json('token');

    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/logout');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Logout successful',
        ]);
});
