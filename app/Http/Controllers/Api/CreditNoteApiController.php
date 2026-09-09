<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CreditNote;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreditNoteApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = CreditNote::query()->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('credit_note_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $creditNotes = $query->paginate($request->integer('per_page', 15));

        return response()->json($creditNotes);
    }

    public function show(CreditNote $creditNote): JsonResponse
    {
        $creditNote->load('invoice');

        return response()->json([
            'credit_note' => array_merge($creditNote->toArray(), [
                'status_color' => $creditNote->status_color,
                'invoice_number' => $creditNote->invoice?->invoice_number,
            ]),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'invoice_id' => 'nullable|exists:invoices,id',
            'customer_name' => 'required|string|max:255',
            'customer_contact' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:1000',
        ]);

        $creditNote = CreditNote::create([
            'credit_note_number' => 'CN-' . date('Y') . '-' . str_pad(CreditNote::count() + 1, 4, '0', STR_PAD_LEFT),
            'invoice_id' => $data['invoice_id'] ?? null,
            'customer_name' => $data['customer_name'],
            'customer_contact' => $data['customer_contact'] ?? null,
            'amount' => $data['amount'],
            'reason' => $data['reason'] ?? null,
            'status' => 'issued',
            'created_by' => $request->user()?->id,
        ]);

        return response()->json(['success' => true, 'credit_note' => $creditNote], 201);
    }

    public function update(Request $request, CreditNote $creditNote): JsonResponse
    {
        $data = $request->validate([
            'reason' => 'nullable|string|max:1000',
            'status' => 'sometimes|string|in:issued,applied,voided',
        ]);

        $creditNote->update($data);

        return response()->json(['success' => true, 'credit_note' => $creditNote->fresh()]);
    }

    public function destroy(CreditNote $creditNote): JsonResponse
    {
        $creditNote->delete();

        return response()->json(['success' => true]);
    }

    public function apply(Request $request, CreditNote $creditNote): JsonResponse
    {
        if ($creditNote->status !== 'issued') {
            return response()->json(['error' => 'Only issued credit notes can be applied.'], 422);
        }

        $creditNote->update(['status' => 'applied']);

        return response()->json(['success' => true, 'credit_note' => $creditNote->fresh()]);
    }
}
