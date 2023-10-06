<?php

namespace App\Http\Controllers\API\superAdmin;

use App\Http\Controllers\Controller;
use App\Models\product;
use Illuminate\Http\Request;

class ProductSuperController extends Controller
{
    public function delete($id)
    {   
        $product=product::withTrashed()->find($id);
        if(!$product)
            {
                return response()->json(['error' =>'Product not found'],400);
            }
            $product->forceDelete();
            
            return response()->json(['status' =>'deleted sucessfully'],201);
    }
}
