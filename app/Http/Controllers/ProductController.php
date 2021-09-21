<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getData(Request $request,$product_id = null){
        if ($product_id != null) {
            # code...
            $products = Product::where('id',$product_id)->get();
            // $products = Product::find($product_id);
            // dd($products);
            if (count($products) !=0) {
                # code...
                return response()->json([
                    'status' => 1,
                    'message' => 'Sukses',
                    'data' => [
                        'product' => $products
                    ]
                ]);
            }else{
                return response()->json([
                    'status' => 0,
                    'message' => 'Not Found',
                    'data' => null
                ]);
            }
        }else{
            $products = Product::get();
            // $products = Product::find($product_id);
            // dd($products);
                # code...
            return response()->json([
                'status' => 1,
                'message' => 'Sukses',
                'data' => [
                    'product' => $products
                ]
            ]);
        }
    }


    public function store(Request $request){

        $result = Product::create([
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
            'product_picture' => $request->product_picture,
        ]);

        if ($result) {
            # code...
            return response()->json([
                'status' => 1,
                'message' => 'Sukses',
                'data' => [
                    'product' => $result
                ]
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Gagal',
                'data' => [
                    'product' => null
                ]
            ]);
        }
    }
}
