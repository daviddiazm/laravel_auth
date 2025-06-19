<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "price" => "required"
        ]);
        if (!$validator) {
            $data = [
                'message' => "no se pude guardar el producto",
                'erros' => $validator->errors()->all(),
                'status' => 422
            ];
            return response()->json($data, 422);
        }
        $product = Product::create([
            "name" => $request->get("name"),
            "price" => $request->get("price"),
        ]);
        return response()->json($product, 201);
    }

    public function getProducts() {
        $products = Product::all();
        if($products->isEmpty()) {
            $data = [
                "message" => "no hay productos"
            ];
            return response()->json($data, 200);
        }
        return response()->json($products, 200);
    }

    public function getProductById($id) {
        $product = Product::find($id);
        if(!$product){
            $data = [
                "message" => "no hay productos"
            ];
            return response()->json($data, 200);
        }
        return response()->json($product, 200);
    }

    public function updateProduct(Request $request, $id) {
        $product = Product::find($id);
        if(!$product){
            $data = [
                "message" => "no hay productos"
            ];
            return response()->json($data, 200);
        }
        $validator = Validator::make($request->all(),[
            "name"=>"somtimes",
            "price"=>"somtimes",
        ]);
        if (!$validator) {
            $data = [
                'message' => "no se pude guardar el producto",
                'erros' => $validator->errors()->all(),
                'status' => 422
            ];
            return response()->json($data, 422);
        }
        if($request->has('name')) {
            $product->name = $request->name;
        }
        if($request->has('price')) {
            $product->price = $request->price;
        }

        $product->update();

        return response()->json(["message"=>"se actualizo correctamente"], 201);
    }

    public function deleteProduct($id) {
        $product = Product::find($id);
        if(!$product){
            $data = [
                "message" => "no hay productos"
            ];
            return response()->json($data, 200);
        }
        $product->delete();
        return response()->json(["message"=> "se elimino exitosamente"], 200);
    }

}
