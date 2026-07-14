<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('is_box_category')->default(false);
            $table->integer('box_limit')->nullable();
            $table->decimal('box_price', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'is_box_category',
                'box_limit',
                'box_price'
            ]);
        });
    }
};
