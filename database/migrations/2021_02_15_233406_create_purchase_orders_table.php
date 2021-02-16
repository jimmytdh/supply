<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_no');
            $table->date('po_date');
            $table->string('procurement_mode');
            $table->string('bac_no')->nullable();
            $table->integer('supplier_id');
            $table->date('delivery_date')->nullable();
            $table->string('delivery_term');
            $table->string('payment_term')->nullable();
            $table->integer('total_amount');
            $table->string('fund_source');
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
        Schema::dropIfExists('purchase_orders');
    }
}
