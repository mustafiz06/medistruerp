<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();

            // Link to purchase order
            $table->unsignedBigInteger('purchase_order_id');

            // Amount paid for this transaction
            $table->decimal('paid_amount', 12, 2);

            // Payment date
            $table->date('payment_date');

            // Optional notes for reference
            $table->text('notes')->nullable();

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');

            // Optional index for faster queries
            $table->index('purchase_order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_payments');
    }
};
