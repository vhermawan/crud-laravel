<?php

namespace App\Http\Controllers;

use App\Http\Resources\FoodResource;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Food2Controller extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $foods = DB::table('food')->get();

        return $this->successResponse(FoodResource::collection($foods));
    }

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $foods = DB::table('food')
            ->where('name', 'like', '%'.$validated['name'].'%')
            ->orderBy('name')
            ->get();

        return $this->successResponse(FoodResource::collection($foods));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $id = DB::table('food')->insertGetId(array_merge($validated, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        $food = DB::table('food')->where('id', $id)->first();

        return $this->createdResponse(new FoodResource($food));
    }

    public function show(int $food): JsonResponse
    {
        $food = DB::table('food')->where('id', $food)->first();

        if (! $food) {
            return $this->notFoundResponse();
        }

        return $this->successResponse(new FoodResource($food));
    }

    public function update(Request $request, int $food): JsonResponse
    {
        $food = DB::table('food')->where('id', $food)->first();

        if (! $food) {
            return $this->notFoundResponse();
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
        ]);

        DB::table('food')
            ->where('id', $food->id)
            ->update(array_merge($validated, ['updated_at' => now()]));

        $food = DB::table('food')->where('id', $food->id)->first();

        return $this->successResponse(new FoodResource($food), 'Data berhasil diupdate');
    }

    public function destroy(int $food): JsonResponse
    {
        $food = DB::table('food')->where('id', $food)->first();

        if (! $food) {
            return $this->notFoundResponse();
        }

        DB::table('food')->where('id', $food->id)->delete();

        return $this->successResponse(message: 'Data berhasil dihapus');
    }
}
