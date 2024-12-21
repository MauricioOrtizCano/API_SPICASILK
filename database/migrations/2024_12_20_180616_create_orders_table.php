<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Relación con el usuario
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Detalles de la orden
            $table->decimal('total_amount', 10, 2)->required();
            $table->string('order_number')->unique()->required();
            $table->enum('status', [
                'pending',
                'processing',
                'completed',
                'declined',
                'cancelled',
            ])->default('pending');

            // Información de envío
            $table->string('shipping_fullname')->required();
            $table->string('shipping_address')->required();
            $table->string('shipping_city')->required();
            $table->string(('shipping_phone'))->required();
            $table->string('shipping_notes')->nullable();

            // Información de pago
            $table->string('payment_method')->required();
            $table->string('payment_status')->nullable();
            $table->string('payment_id')->nullable();

            // Metadatos
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
