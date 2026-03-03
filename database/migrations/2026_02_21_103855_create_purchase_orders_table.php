<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->unsignedBigInteger('supplier_id');
            $table->date('order_date');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0.00);
            $table->decimal('due_amount', 12, 2)->default(0.00);
            $table->enum('status', ['pending', 'completed', 'cancel'])->default('pending');
            $table->text('status_notes')->nullable();
            $table->timestamp('status_changed_at')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            
            // Indexes for performance
            $table->index('status');
            $table->index('order_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
};