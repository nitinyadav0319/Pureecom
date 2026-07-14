<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mega_menu_column_category', function (Blueprint $table) {
            $table->id();

            // Matches mega_menu_columns.id type (BIGINT unsigned)
            $table->unsignedBigInteger('mega_menu_column_id');

            // Matches categories.id type (INT unsigned)
            $table->unsignedInteger('category_id');

            $table->timestamps();

            // Foreign keys
            $table->foreign('mega_menu_column_id')
                  ->references('id')
                  ->on('mega_menu_columns')
                  ->onDelete('cascade');

            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mega_menu_column_category');
    }
};
