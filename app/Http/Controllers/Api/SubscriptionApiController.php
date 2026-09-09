<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Subscription::query()->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('subscription_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('plan_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($cycle = $request->input('billing_cycle')) {
            $query->where('billing_cycle', $cycle);
        }

        $subscriptions = $query->paginate($request->integer('per_page', 15));

        return response()->json($subscriptions);
    }

    public function show(Subscription $subscription): JsonResponse
    {
        return response()->json([
            'subscription' => array_merge($subscription->toArray(), [
                'status_color' => $subscription->status_color,
            ]),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_contact' => 'nullable|string|max:255',
            'plan_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'billing_cycle' => 'required|string|in:daily,weekly,monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'next_billing_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $subscription = Subscription::create([
            'subscription_number' => 'SUB-' . date('Y') . '-' . str_pad(Subscription::count() + 1, 4, '0', STR_PAD_LEFT),
            'customer_name' => $data['customer_name'],
            'customer_contact' => $data['customer_contact'] ?? null,
            'plan_name' => $data['plan_name'],
            'amount' => $data['amount'],
            'billing_cycle' => $data['billing_cycle'],
            'status' => 'active',
            'start_date' => $data['start_date'],
            'next_billing_date' => $data['next_billing_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'notes' => $data['notes'] ?? null,
            'created_by' => $request->user()?->id,
        ]);

        return response()->json(['success' => true, 'subscription' => $subscription], 201);
    }

    public function update(Request $request, Subscription $subscription): JsonResponse
    {
        $data = $request->validate([
            'plan_name' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric|min:0',
            'billing_cycle' => 'sometimes|string|in:daily,weekly,monthly,quarterly,yearly',
            'status' => 'sometimes|string|in:active,paused,cancelled,expired',
            'next_billing_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $subscription->update($data);

        return response()->json(['success' => true, 'subscription' => $subscription->fresh()]);
    }

    public function destroy(Subscription $subscription): JsonResponse
    {
        $subscription->delete();

        return response()->json(['success' => true]);
    }

    public function pause(Subscription $subscription): JsonResponse
    {
        if ($subscription->status !== 'active') {
            return response()->json(['error' => 'Only active subscriptions can be paused.'], 422);
        }

        $subscription->update(['status' => 'paused']);

        return response()->json(['success' => true, 'subscription' => $subscription->fresh()]);
    }

    public function resume(Subscription $subscription): JsonResponse
    {
        if ($subscription->status !== 'paused') {
            return response()->json(['error' => 'Only paused subscriptions can be resumed.'], 422);
        }

        $subscription->update(['status' => 'active']);

        return response()->json(['success' => true, 'subscription' => $subscription->fresh()]);
    }

    public function cancel(Subscription $subscription): JsonResponse
    {
        if (in_array($subscription->status, ['cancelled', 'expired'])) {
            return response()->json(['error' => 'Subscription is already cancelled or expired.'], 422);
        }

        $subscription->update(['status' => 'cancelled', 'end_date' => now()->toDateString()]);

        return response()->json(['success' => true, 'subscription' => $subscription->fresh()]);
    }
}
