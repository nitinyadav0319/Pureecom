<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mega_menu_columns', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g. "Shop by Age"
            $table->enum('type', ['variation', 'brand'])->default('variation'); // column type
            $table->unsignedBigInteger('variation_id')->nullable(); // if type = variation
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('variation_id')
                  ->references('id')
                  ->on('variations')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mega_menu_columns');
    }
};
