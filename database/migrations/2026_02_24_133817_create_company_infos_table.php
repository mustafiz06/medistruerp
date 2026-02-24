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
        Schema::create('company_infos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Medistru ERP');
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->decimal('default_vat_percent', 5, 2)->default(0);
            $table->decimal('default_ait_percent', 5, 2)->default(0);
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('invoice_prefix')->default('INV-');
            $table->string('challan_prefix')->default('CN-');
            $table->string('po_prefix')->default('PO-');
            $table->integer('invoice_start_number')->default(1);
            $table->string('invoice_footer_title')->nullable();
            $table->text('footer_message')->nullable();
            $table->string('currency_name')->default('Taka');
            $table->string('currency_symbol', 10)->default('৳');
            $table->string('currency_position')->default('left'); 
            $table->string('registration_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_infos');
    }
};
