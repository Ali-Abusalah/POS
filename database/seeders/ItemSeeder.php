<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Wireless Mouse', 'code' => 'WM-001', 'barcode' => '8901234567890', 'category' => 'electronics', 'unit' => 'piece', 'pre_tax_price' => 25.00, 'description' => 'Ergonomic wireless mouse with USB receiver'],
            ['name' => 'USB-C Cable 1m', 'code' => 'UC-002', 'barcode' => '8901234567891', 'category' => 'electronics', 'unit' => 'piece', 'pre_tax_price' => 8.50, 'description' => 'Fast charging USB-C cable'],
            ['name' => 'Notebook A5', 'code' => 'NB-003', 'barcode' => '8901234567892', 'category' => 'stationery', 'unit' => 'piece', 'pre_tax_price' => 3.75, 'description' => 'Lined notebook, 100 pages'],
            ['name' => 'Ballpoint Pen (Box/12)', 'code' => 'BP-004', 'barcode' => '8901234567893', 'category' => 'stationery', 'unit' => 'box', 'pre_tax_price' => 6.00, 'description' => 'Blue ballpoint pens, pack of 12'],
            ['name' => 'Desk Lamp LED', 'code' => 'DL-005', 'barcode' => '8901234567894', 'category' => 'electronics', 'unit' => 'piece', 'pre_tax_price' => 18.00, 'description' => 'Adjustable LED desk lamp'],
            ['name' => 'Office Chair Mat', 'code' => 'CM-006', 'barcode' => '8901234567895', 'category' => 'furniture', 'unit' => 'piece', 'pre_tax_price' => 35.00, 'description' => 'Floor protector mat for office chairs'],
            ['name' => 'Whiteboard Marker Set', 'code' => 'WM-007', 'barcode' => '8901234567896', 'category' => 'stationery', 'unit' => 'set', 'pre_tax_price' => 4.50, 'description' => 'Dry erase markers, set of 4 colors'],
            ['name' => 'File Folder (Pack/20)', 'code' => 'FF-008', 'barcode' => '8901234567897', 'category' => 'stationery', 'unit' => 'pack', 'pre_tax_price' => 7.25, 'description' => 'Manila file folders, 20 pack'],
            ['name' => 'Monitor Stand', 'code' => 'MS-009', 'barcode' => '8901234567898', 'category' => 'furniture', 'unit' => 'piece', 'pre_tax_price' => 22.00, 'description' => 'Adjustable monitor riser/stand'],
            ['name' => 'Keyboard Wireless', 'code' => 'KB-010', 'barcode' => '8901234567899', 'category' => 'electronics', 'unit' => 'piece', 'pre_tax_price' => 30.00, 'description' => 'Full-size wireless keyboard'],
            ['name' => 'Stapler Heavy Duty', 'code' => 'ST-011', 'barcode' => '8901234567900', 'category' => 'stationery', 'unit' => 'piece', 'pre_tax_price' => 12.50, 'description' => 'Desktop stapler, 25-sheet capacity'],
            ['name' => 'Tape Dispenser', 'code' => 'TD-012', 'barcode' => '8901234567901', 'category' => 'stationery', 'unit' => 'piece', 'pre_tax_price' => 5.00, 'description' => 'Desktop tape dispenser with tape'],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
