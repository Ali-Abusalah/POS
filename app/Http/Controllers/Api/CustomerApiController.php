<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Customer::query()->orderBy('name');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->withCount('invoices')->paginate($request->integer('per_page', 15));

        $customers->getCollection()->transform(function ($customer) {
            $customer->total_purchases = round($customer->totalPurchases(), 2);

            return $customer;
        });

        return response()->json($customers);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $customer = Customer::create($data);

        \App\Models\ActivityLog::log('create', Customer::class, $customer->id, "Customer {$customer->name} created");

        return response()->json(['customer' => $customer], 201);
    }

    public function update(Request $request, Customer $customer): JsonResponse
    {
        $customer->update($this->validated($request, $customer->id));

        \App\Models\ActivityLog::log('update', Customer::class, $customer->id, "Customer {$customer->name} updated");

        return response()->json(['customer' => $customer->fresh()]);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json(['success' => true]);
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', 'max:255', \Illuminate\Validation\Rule::unique('customers', 'email')->ignore($ignoreId)],
            'phone' => ['nullable', 'string', 'max:255', \Illuminate\Validation\Rule::unique('customers', 'phone')->ignore($ignoreId)],
            'address' => 'nullable|string',
            'balance' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
    }
}
