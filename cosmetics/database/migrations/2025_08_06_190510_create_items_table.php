<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->string('item_color')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('price_unit_ind', 10 , 2)->default(0);
            $table->decimal('price_dozen', 10 , 2)->default(0);
            $table->decimal('price_unit_ph', 10 , 2)->default(0);
            $table->decimal('cost', 10 , 2);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
