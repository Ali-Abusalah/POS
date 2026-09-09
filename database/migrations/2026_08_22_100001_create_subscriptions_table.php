<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('subscription_number')->unique();
            $table->string('customer_name');
            $table->string('customer_contact')->nullable();
            $table->string('plan_name');
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('billing_cycle')->default('monthly'); // daily, weekly, monthly, quarterly, yearly
            $table->string('status')->default('active'); // active, paused, cancelled, expired
            $table->date('start_date');
            $table->date('next_billing_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
