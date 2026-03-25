<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\DAO\ProductDAOInterface;

class ProductController extends Controller
{
    protected $productDAO;

    public function __construct(ProductDAOInterface $productDAO)
    {
        $this->productDAO = $productDAO;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = $this->productDAO->getAll();

        return response()->json($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $product = $this->productDAO->findBySlug($slug);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
