<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Campos basicos del producto
            $table->string('name')->required()->unique();
            $table->text('description')->required();

            // Campos de precio y stock
            $table->decimal('price', 8, 2)->required(); // Precio con 2 decimales
            $table->integer('stock')->default(0);

            // Relación con otras entidades
            $table->foreignId('category_id')
                ->nullable()
                ->constrained() // Esta función se utiliza para que la columna category_id haga referencia a una tabla llamada categories
                ->onDelete('set null'); // Permite que el producto exista si la categoría se elimina
            
            // Campos para gestión del producto
            $table->string('sku')->unique()->required(); // Código del producto único
            $table->string('image_url')->required();

            // Campos de estado del producto
            $table->boolean('is_active')->default(true); // productos activo o inactivo
            $table->boolean('is_featured')->default(false); // producto destacado?

            // Campos para variantes del producto
            $table->json('colors')->required();

            // Metadatos
            $table->timestamps();

            // Indices para mejorar el desempeño de busqueda
            $table->index('name');
            $table->index('price');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};