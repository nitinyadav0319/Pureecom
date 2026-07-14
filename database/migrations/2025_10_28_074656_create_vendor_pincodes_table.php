<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vendor_pincodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('pincode_id');
            $table->timestamps();

            $table->foreign('vendor_id')
                ->references('id')->on('vendor_profiles')
                ->onDelete('cascade');

            $table->foreign('pincode_id')
                ->references('id')->on('pin_codes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_pincodes');
    }
};
