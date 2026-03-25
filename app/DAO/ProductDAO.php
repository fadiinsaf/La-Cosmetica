<?php

namespace App\DAO;

use App\Models\Product;

class ProductDAO implements ProductDAOInterface
{
    public function getAll()
    {
        return Product::with(['category', 'images'])->where('stock', '>', 0)->get();
    }

    public function findBySlug(string $slug)
    {
        return Product::with(['category', 'images'])
            ->where('slug', $slug)
            ->first();
    }

    public function findById(int $id)
    {
        return Product::with(['category', 'images'])->find($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(int $id, array $data)
    {
        $product = Product::find($id);

        if (! $product) {
            return null;
        }

        $product->update($data);

        return $product;
    }

    public function delete(int $id)
    {
        $product = Product::find($id);

        if (! $product) {
            return false;
        }

        $product->delete();

        return true;
    }
}