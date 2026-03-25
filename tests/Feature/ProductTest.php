<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('user can get product by slug', function () {
    $category = Category::create([
        'name' => 'Huiles',
        'slug' => 'huiles',
        'description' => 'Les huiles naturelles',
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Huile Argan Bio',
        'slug' => Str::slug('Huile Argan Bio'),
        'description' => 'Huile naturelle',
        'price' => 120,
        'stock' => 10,
    ]);

    $response = $this->getJson('/api/products/' . $product->slug);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'Huile Argan Bio',
            'slug' => 'huile-argan-bio',
        ]);
});

test('product can be created with slug', function () {
    $category = Category::create([
        'name' => 'Cremes',
        'slug' => 'cremes',
        'description' => 'Les cremes',
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Cream Hydratante Bio',
        'slug' => Str::slug('Cream Hydratante Bio'),
        'description' => 'Bonne creme',
        'price' => 90,
        'stock' => 5,
    ]);

    expect($product->slug)->toBe('cream-hydratante-bio');
});

test('getting unknown slug returns 404', function () {
    $response = $this->getJson('/api/products/produit-introuvable');

    $response->assertStatus(404);
});