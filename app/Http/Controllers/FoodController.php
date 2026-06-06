<?php

namespace App\Http\Controllers;

use App\Http\Resources\FoodResource;
use App\Http\Traits\ApiResponse;
use App\Models\Food;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoodController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $foods = Food::all();

        return $this->successResponse(FoodResource::collection($foods));
    }

    /**
     * Search foods by name using raw SQL with parameter bindings.
     * Demonstrates safe raw SQL — user input never interpolated into query string.
     *
     * UNSAFE (never do this):
     *   DB::select("SELECT * FROM foods WHERE name = '$name'");
     *
     * SAFE (bindings via prepared statements):
     *   DB::select('SELECT * FROM foods WHERE name LIKE ?', ["%$name%"]);
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $foods = DB::select(
            'SELECT * FROM foods WHERE name LIKE ? ORDER BY name ASC',
            ['%'.$validated['name'].'%']
        );

        return $this->successResponse($foods);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $food = Food::create($validated);

        return $this->createdResponse(new FoodResource($food));
    }

    public function show(Food $food): JsonResponse
    {
        return $this->successResponse(new FoodResource($food));
    }

    public function update(Request $request, Food $food): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
        ]);

        $food->update($validated);

        return $this->successResponse(new FoodResource($food), 'Data berhasil diupdate');
    }

    public function destroy(Food $food): JsonResponse
    {
        $food->delete();

        return $this->successResponse(message: 'Data berhasil dihapus');
    }

    public function trashed(): JsonResponse
    {
        $foods = Food::onlyTrashed()->get();

        return $this->successResponse(FoodResource::collection($foods));
    }

    public function restore(int $id): JsonResponse
    {
        $food = Food::onlyTrashed()->findOrFail($id);
        $food->restore();

        return $this->successResponse(new FoodResource($food), 'Data berhasil dipulihkan');
    }

    public function forceDelete(int $id): JsonResponse
    {
        $food = Food::onlyTrashed()->findOrFail($id);
        $food->forceDelete();

        return $this->successResponse(message: 'Data berhasil dihapus permanen');
    }
}
