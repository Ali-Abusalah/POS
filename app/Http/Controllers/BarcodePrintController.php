<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class BarcodePrintController extends Controller
{
    public function printSingle(Item $item, Request $request)
    {
        $copies = max(1, min(50, (int) $request->get('copies', 1)));

        return view('barcodes.print', [
            'items' => collect([$item]),
            'copies' => $copies,
        ]);
    }

    public function printBulk(Request $request)
    {
        $ids = $request->get('ids', '');
        $copies = max(1, min(50, (int) $request->get('copies', 1)));

        $items = Item::whereIn('id', explode(',', $ids))->get();

        if ($items->isEmpty()) {
            abort(404, 'No items found');
        }

        return view('barcodes.print', [
            'items' => $items,
            'copies' => $copies,
        ]);
    }
}
