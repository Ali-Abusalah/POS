<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('card_amount', 10, 2)->default(0)->after('amount_paid');
            $table->decimal('other_amount', 10, 2)->default(0)->after('card_amount');
            $table->string('other_payment_method')->nullable()->after('other_amount');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['card_amount', 'other_amount', 'other_payment_method']);
        });
    }
};
