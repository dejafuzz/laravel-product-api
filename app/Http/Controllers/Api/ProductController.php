<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse {

        $search = $request->query('search');
        $category = $request->query('category');
        $limit = (int) $request->query('limit', 10);
        $page = (int) $request->query('page', 1);

        $query = Product::query();

        if ($search) {
            $query->where('title', 'ilike', "%{$search}%");
        }

        if ($category) {
            $query->where('category', $category);
        }

        $products = $query->orderBy('created_at', 'desc')
                    ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'meta' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse {

        $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => "Product with ID {$id} not found",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    public function store(Request $request): JsonResponse {
        
        try {

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'category' => 'required|string|max:100',
                'images' => 'required|array|min:1',
                'images.*' => 'url',
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);

        }

        $user = $request->user();

        $product = Product::create([
            ...$validated,
            'created_by'=> $user->username,
            'created_by_id' => $user->id,
            'updated_by' => $user->username,
            'updated_by_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);

    }

    public function update(Request $request, int $id): JsonResponse {

        $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => "Product with ID {$id} not found",
            ], 404);
        }

        try {

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'price' => 'sometimes|numeric|min:0',
                'description' => 'nullable|string',
                'category' => 'sometimes|string|max:100',
                'images' => 'sometimes|array|min:1',
                'images.*' => 'url',
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);

        }

        $user = $request->user();

        $product->update([
            ...$validated,
            'updated_by' => $user->username,
            'updated_by_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product->fresh(),
        ]);

    }

    public function destroy(int $id): JsonResponse {

        $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => "Product with ID {$id} not found",
            ], 404);
        }
        
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ]);
    }
}