<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionColumnsToOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::table('order_items', function (Blueprint $table) {
        $table->decimal('admin_commission', 10, 2)->default(0);
        $table->decimal('vendor_earning', 10, 2)->default(0);
    });
}

public function down()
{
    Schema::table('order_items', function (Blueprint $table) {
        $table->dropColumn(['admin_commission', 'vendor_earning']);
    });
}

}
