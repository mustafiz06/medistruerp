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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            // Movement Type
            $table->enum('type', ['in', 'out'])->default('in');
            
            // Quantity
            $table->decimal('quantity', 19, 4)->default(0);
            
            // Reference (PO Number, Return Number, etc.)
            $table->string('reference')->nullable();
            
            // Notes/Description
            $table->text('notes')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('product_id');
            $table->index('type');
            $table->index('reference');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
};