<?php

namespace App\Http\Controllers\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class productController extends Controller
{
    public function getAllProducts() {
        // Muestra todos los productos
        $allProducts = Product::all();

        if ($allProducts->isEmpty()) {
            $data = [
                'message' => 'No se encontraron productos',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        return response()->json($allProducts, 202);
    }

    public function postProduct(Request $request) {
        // Crea un nuevo producto
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'integer|min:0',
            'sku' => 'string|unique:products,sku',
            'image_url' => 'required|url',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'colors' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 422
            ];
            return response()->json($data, 422);
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'sku' => Str::uuid(),
            'image_url' => $request->image_url,
            'is_active' => $request->is_active,
            'is_featured' => $request->is_featured,
            'colors' => $request->colors
        ]);

        if (!$product) {
            $data = [
                'message' => 'Error al crear producto',
                'errors' => $validator->errors(),
                'status' => 500
            ];
            return response()->json($data, 500);
        };

        $data = [
            'product' => $product,
            'status' => 201
        ];

        return response()->json($data, 201);

    }



    public function deleteProductbyID($id) {
        // Elimina un producto por su ID
        $product = Product::find($id);

        if (!$product) {
            $data = [
                "message" => "El ID $id de producto no fue encontrado",
                "status" => 404
            ];

            return response()->json($data, 404);
        }

        $product->delete();

        $data = [
            "message" => "Producto eliminado satisfactoriamente",
            "status" => 200
        ];

        return response()->json($data, 200);
        
    }


    public function getProductByID($id) {
        // Obtiene un producto por su ID
        $product = Product::find($id);

        if (!$product) {
            $data = [
                "message" => "El ID $id de producto no fue encontrado",
                "status" => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            "message" => "Producto encontrado",
            "product" => $product,
            "status" => 200
        ];

        return response()->json($data, 200);
    }


    public function updateProduct(Request $request, $id) {
        // Actualiza todas las variables de un producto
        $product = Product::find($id);

        if (!$product) {
            $data = [
                "message" => "El ID $id de producto no fue encontrado",
                "status" => 404
            ];

            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'integer|min:0',
            'image_url' => 'required|url',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'colors' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 422
            ];
            return response()->json($data, 422);
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->image_url = $request->image_url;
        $product->is_active = $request->is_active;
        $product->is_featured = $request->is_featured;
        $product->colors = $request->colors;

        $product->save();

        $data = [
            "message" => "El producto con ID $id fue actualizado exitosamente",
            "producto" => $product,
            "status" => 200
        ];

        return response()->json($data, 200);

    }


    public function updatePartialProduct(Request $request, $id) {
        //Actualiza algunas propiedades de un producto
        $product = Product::find($id);

        if (!$product) {
            $data = [
                "message" => "El ID $id de producto no fue encontrado",
                "status" => 404
            ];

            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric|min:0',
            'stock' => 'integer|min:0',
            'image_url' => 'url',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'colors',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 422
            ];
            return response()->json($data, 422);
        }


        if ($request->has('name')) {
            $product->name = $request->name;
        }

        if ($request->has('description')) {
            $product->description = $request->description;
        } 

        if ($request->has('price')) {
            $product->price = $request->price;
        } 

        if ($request->has('stock')) {
            $product->stock = $request->stock;
        }

        if ($request->has('image_url')) {
            $product->image_url = $request->image_url;
        }

        if ($request->has('is_active')) {
            $product->is_active = $request->is_active;
        } 

        if ($request->has('is_featured')) {
            $product->is_featured = $request->is_featured;
        } 

        if ($request->has('colors')) {
            $product->colors = $request->colors;
        }

        $product->save();

        $data = [
            "message" => "El producto con ID $id fue actualizado exitosamente",
            "producto" => $product,
            "status" => 200
        ];

        return response()->json($data, 200);
    }


    
}
