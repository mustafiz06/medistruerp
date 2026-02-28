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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 50)->unique()->index();
            $table->enum('customer_type', ['individual', 'organization'])->default('individual')->index();
            
            $table->string('name', 150)->nullable();
            $table->string('designation')->nullable();
            $table->string('work_place', 150)->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('assistant_name', 100)->nullable();
            $table->string('assistant_phone', 20)->nullable();

            $table->string('company_name', 150)->nullable();
            $table->string('contact_person', 100)->nullable();
            $table->string('contact_person_position', 50)->nullable();
            $table->string('contact_person_phone', 50)->nullable();
            $table->string('bin_no', 50)->nullable()->index();

            $table->string('email', 150)->nullable()->index();
            $table->string('phone', 20)->nullable()->index();
            $table->text('address')->nullable();
            $table->decimal('credit_limit', 15, 2)->default(0.00);
            $table->decimal('outstanding_balance', 15, 2)->default(0.00);
            $table->decimal('due_amount', 15, 2)->default(0.00);
            $table->decimal('available_credit', 15, 2)->default(0.00);
            $table->string('status', 20)->default('active')->index();
            $table->string('priority', 20)->default('normal');
            $table->unsignedBigInteger('sales_representative_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('first_purchase_date')->nullable();
            $table->timestamp('last_purchase_date')->nullable();
            $table->timestamp('last_contact_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['customer_type', 'status']);
            $table->index(['created_at', 'updated_at']);
            $table->index(['outstanding_balance', 'due_amount']);
            $table->foreign('sales_representative_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
