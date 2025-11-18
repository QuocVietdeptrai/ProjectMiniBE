<?php
// database/migrations/2025_11_04_143813_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->date('order_date');
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending');
            $table->string('payment_method', 50)->default('cash');
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};