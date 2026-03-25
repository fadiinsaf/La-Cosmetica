<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

test('admin can create category', function () {
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password123'),
        'role' => 'admin',
    ]);

    $token = JWTAuth::fromUser($admin);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/admin/categories', [
            'name' => 'Cremes Bio',
            'slug' => Str::slug('Cremes Bio'),
            'description' => 'Categorie des cremes bio',
        ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('categories', [
        'name' => 'Cremes Bio',
    ]);
});

test('non admin cannot create category', function () {
    $user = User::create([
        'name' => 'Client',
        'email' => 'client@example.com',
        'password' => bcrypt('password123'),
        'role' => 'client',
    ]);

    $token = JWTAuth::fromUser($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/admin/categories', [
            'name' => 'Cremes Bio',
            'description' => 'Categorie des cremes bio',
        ]);

    $response->assertStatus(403);
});

test('admin can update category', function () {
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password123'),
        'role' => 'admin',
    ]);

    $category = Category::create([
        'name' => 'Old Name',
        'slug' => Str::slug('Old Name'),
        'description' => 'Old description',
    ]);

    $token = JWTAuth::fromUser($admin);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->putJson('/api/admin/categories/' . $category->id, [
            'name' => 'New Name',
            'slug' => Str::slug('New Name'),
            'description' => 'New description',
        ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'New Name',
    ]);
});

test('admin can delete category', function () {
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password123'),
        'role' => 'admin',
    ]);

    $category = Category::create([
        'name' => 'To Delete',
        'slug' => Str::slug('To Delete'),
        'description' => 'Delete me',
    ]);

    $token = JWTAuth::fromUser($admin);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->deleteJson('/api/admin/categories/' . $category->id);

    $response->assertStatus(200);

    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});