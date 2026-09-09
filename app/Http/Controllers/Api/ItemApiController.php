<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Item::query()->latest();

        if ($search = $request->input('q')) {
            $query->search($search);
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        $items = $query->paginate($request->integer('per_page', 15));

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $data['code'] = $data['code'];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);

        \App\Models\ActivityLog::log('create', Item::class, $item->id, "Item {$item->name} created");

        return response()->json(['item' => $item], 201);
    }

    public function update(Request $request, Item $item): JsonResponse
    {
        $data = $this->validated($request, $item->id);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        \App\Models\ActivityLog::log('update', Item::class, $item->id, "Item {$item->name} updated");

        return response()->json(['item' => $item->fresh()]);
    }

    public function destroy(Item $item): JsonResponse
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();

        return response()->json(['success' => true]);
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('items', 'code')->ignore($ignoreId)],
            'barcode' => ['nullable', 'string', 'max:255', \Illuminate\Validation\Rule::unique('items', 'barcode')->ignore($ignoreId)],
            'category' => 'nullable|in:general,electronics,groceries,clothing,furniture,stationery',
            'unit' => 'nullable|string|max:255',
            'pre_tax_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
    }
}
