<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add discount fields to invoice_items
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('discount_value', 10, 2)->default(0)->after('line_total');
            $table->string('discount_type')->default('none')->after('discount_value'); // none, percentage, fixed
        });

        // Add discount fields to invoices
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('discount_value', 10, 2)->default(0)->after('tax_total');
            $table->string('discount_type')->default('none')->after('discount_value'); // none, percentage, fixed
            $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_type');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['discount_value', 'discount_type']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['discount_value', 'discount_type', 'discount_amount']);
        });
    }
};
