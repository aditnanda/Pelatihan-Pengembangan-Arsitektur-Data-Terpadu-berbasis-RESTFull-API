<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function getData(Request $request,$product_id = null){
        $user = auth('api')->user();

        if (!$user) {
            # code...
            return response()->json([
                'status' => 0,
                'message' => 'Unathorized'
            ]);
        }

        if ($product_id != null) {
            # code...
            $products = Product::where('id',$product_id)->get();
            // $products = Product::find($product_id);
            // dd($products);
            foreach ($products as $key => $value) {
                # code...
                if ($value->product_picture) {
                    # code...
                    $value->product_picture = asset('storage/products/pictures/'.$value->product_picture);

                }
            }
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
            foreach ($products as $key => $value) {
                # code...
                if ($value->product_picture) {
                    # code...
                    $value->product_picture = asset('storage/products/pictures/'.$value->product_picture);

                }          
            }
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
        $user = auth('api')->user();

        if (!$user) {
            # code...
            return response()->json([
                'status' => 0,
                'message' => 'Unathorized'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|max:100',
            'product_price' => 'required|max:100',
            'product_description' => 'nullable|max:100',
            'product_picture' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors(),
                ], 400);
        }

        // dd($request->product_picture);
        if ($request->product_picture) {
            # code...
            $random = time();
            $request->product_picture->storeAs('public/products/pictures/',$random.'-'.$request->product_picture->getClientOriginalName());
            $request->product_picture = $random.'-'.$request->product_picture->getClientOriginalName();    
    
        }

        $result = Product::create([
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
            'product_picture' => $request->product_picture,
        ]);

        if ($result) {
            $result->product_picture = asset('storage/products/pictures/'.$result->product_picture);
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


    public function update(Request $request,$id){
        $user = auth('api')->user();

        if (!$user) {
            # code...
            return response()->json([
                'status' => 0,
                'message' => 'Unathorized'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|max:100',
            'product_price' => 'required|max:100',
            'product_description' => 'nullable|max:100',
            'product_picture' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors(),
                ], 400);
        }

        // dd($request->product_picture);
        if ($request->product_picture) {
            # code...
            $random = time();
            $request->product_picture->storeAs('public/products/pictures/',$random.'-'.$request->product_picture->getClientOriginalName());
            $request->product_picture = $random.'-'.$request->product_picture->getClientOriginalName();    
    
        }

        $result = Product::where('id',$id)->update([
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
            'product_picture' => $request->product_picture,
        ]);

        if ($result) {
            $result = Product::where('id',$id)->get();

            foreach ($result as $key => $value) {
                # code...
                if ($value->product_picture) {
                    # code...
                    $value->product_picture = asset('storage/products/pictures/'.$value->product_picture);

                }          
            }
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

    public function delete(Request $request,$id){
        $user = auth('api')->user();

        if (!$user) {
            # code...
            return response()->json([
                'status' => 0,
                'message' => 'Unathorized'
            ]);
        }
        $result = Product::where('id',$id)->first();
        if ($result) {
            # code...
            if ($result->product_picture) {
                # code...
                $path = \public_path().'/storage/products/pictures/'.$result->product_picture;
                unlink($path);
            }

            $cek = $result->delete();

            if ($cek) {
            
                return response()->json([
                    'status' => 1,
                    'message' => 'Sukses',
                   
                ]);
            }else{
                return response()->json([
                    'status' => 0,
                    'message' => 'Gagal',
                    
                ]);
            }
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Not Found',
                
            ],404);
            
        }

        
    }
}
