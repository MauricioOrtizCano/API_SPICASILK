<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'sku',
        'image_url',
        'is_active',
        'is_featured',
        'colors'
    ];

    // Indica que "colors" debe ser tratado como un array
    protected $casts = [
        'colors' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relación con la Categoria de producto
    // Un producto pertenece a una categoría
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    // Relación con los items de la orden
    // Un producto puede estar en varios items de orden
    public function orderItems(): HasMany {
        return $this->hasMany(OrderItem::class);
    }

    // Relación con el inventario
    // Un producto tiene un inventario
    public function inventory(): HasOne {
        return $this->hasOne(Inventory::class);
    }

    // Relación con los items del carrito
    // Un producto puede estar en varios items del carrito
    public function cartItems(): HasMany {
        return $this->hasMany(CartItem::class);
    }

    // Verifica si el producto tiene stock
    public function inStock(): bool {
        return $this->stock > 0;
    }

    // Verifica si el producto está activo
    public function isActive(): bool {
        return $this->is_active;
    }

    // Aumenta el stock del producto
    public function incrementStock(int $quantity): void {
        $this->increment('stock', $quantity);
    }

    // Disminuye el stock del producto
    public function decrementStock(int $quantity): void {
        $this->decrement('stock', $quantity);
    }

    // Scope para productos activos
    public function scopeActive($query) {
        return $query->where('is_active', true);
    }

    // Scope para productos destacados
    public function scopeFeatured($query) {
        return $query->where('is_featured', true);
    }

    // Scope para productos con stock disponible
    public function scopeInStock($query) {
        return $query->where('stock', '>', 0);
    }

    // Scope para productos por Categoria
    public function scopeByCategory($query, $category) {
        return $query->where('category_id', $category);
    }

    // Scope para buscar productos por nombre
    public function scopeSearch($query, $search) {
        return $query->where('name', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%");
    }

    // Obtener precio formateado
    public function getFormattedPriceAttribute():string {
        return '$' . number_format($this->price, 2);
    }

}
