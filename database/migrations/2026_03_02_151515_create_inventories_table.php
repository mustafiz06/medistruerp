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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->foreignId('product_batch_id')->nullable()->constrained('product_batches')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('product')->nullOnDelete();

            // Opening Stock
            $table->decimal('opening_stock', 19, 4)->default(0);
            $table->decimal('opening_stock_value', 19, 4)->default(0);
            $table->timestamp('opening_stock_date')->nullable();

            // Current Stock Levels
            $table->decimal('current_stock', 19, 4)->default(0);
            $table->decimal('reserved_stock', 19, 4)->default(0);
            $table->decimal('available_stock', 19, 4)->default(0);
            $table->decimal('in_transit_stock', 19, 4)->default(0);
            $table->decimal('damaged_stock', 19, 4)->default(0);

            // Stock Valuation
            $table->decimal('current_stock_value', 19, 4)->default(0);
            $table->decimal('damaged_stock_value', 19, 4)->default(0);

            // Reorder Settings
            $table->unsignedInteger('min_stock_level')->default(0);
            $table->unsignedInteger('max_stock_level')->default(0);
            $table->unsignedInteger('reorder_quantity')->default(0);

            // Inventory Tracking
            $table->timestamp('last_counted_at')->nullable();
            $table->timestamp('last_movement_at')->nullable();
            $table->foreignId('last_counted_by')->nullable()->constrained('users')->nullOnDelete();

            // Status
            $table->boolean('is_active')->default(true);
            $table->string('stock_status')->default('in_stock');

            $table->timestamps();
            $table->softDeletes();

            // Unique Index
            $table->unique(['warehouse_id', 'product_id', 'product_batch_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
};
