<?php

namespace App\Http\Controllers\API;

use App\Models\product;
use App\Models\category;
use App\Models\subcategory;
use Illuminate\Http\Request;
use App\Models\subsubcategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class categoryController extends Controller
{
    public function index()
    {
        $category=category::with('subcategories.subsubcategories')->get();
        return response()->json(['category' => $category],200);

    }
    public function delete($id)
    {
        $category=category::find($id);
        DB::beginTransaction();
        $subcategory=subcategory::find($category->id);
        $subsubcategory=subsubcategory::where('subcategory_id',$subcategory->id)->get();
        foreach ($subsubcategory as $subsub) {
            $subsub->delete(); // This will delete each subsubcategory
        }
        
            // dd($subsubcategory);
        
        $subcategory->delete(); // This will delete each subsubcategory
        $category->delete();
        DB::commit();
        return response()->json(['status'=>'category deleted sucsessfully'],200);
    }
    public function create(Request $request)
    {
        $validator= validator::make($request->all(),[
            'category' =>'required',
            'subcategory' =>'nullable',
            'subsubcategory' =>'nullable',

            
        ]);
        if($validator->fails())
        {
            return response()->json(['Error'=>$validator->errors()],401);        
        }
        if(!$request->has('subcategory')&& $request->has('subsubcategory'))
        {
            return response()->json(['Error'=>"you must provide a subcategory"],401);
        }
        if(!$request->has('category')&& $request->has('subcategory'))
        {
            return response()->json(['Error'=>"you must provide a category"],401);
        }

        $category= new category();
        $category->name=$request->input('category');
        $category->save();
        // create a new  subcategory
        $subcategory= new subcategory();
        if($request->has('subcategory')){

            $subcategory->name=$request->input('subcategory') ;
            $subcategory->category_id=$category->id;
            $subcategory->save();
            

        }
        // create a new  subcategory
        $subsubcategory= new subsubcategory();
        if($request->has('subsubcategory')){
        
            $subsubcategory->name=$request->subsubcategory;
            $subsubcategory->subcategory_id=$subcategory->id;
            $subsubcategory->save();

        }
            return response()->json(["category added successfully"],200);
        }
        public function createSubCategory(Request $request,$id){
            $category =  Category::find($id);
            if(!$category) {
                return response()->json(['Error'=>"category not found"],400);
                
            }
            $subcategory=subcategory::create([
                'name' => $request->name,
                'category_id' => $category->id,
                
                
            ]);
            return response()->json(['status'=>"subcategory added successfully",'data'=>$subcategory],200);
        
    }
        public function createSubsubCategory(Request $request,$id){
            $subcategory =  subcategory::find($id);
            if(!$subcategory) {
                return response()->json(['Error'=>"subcategory not found"],400);
                
            }
            $subsubcategory=subsubcategory::create([
                'name' => $request->name,
                'subcategory_id' => $subcategory->id,
                
                
            ]);
            return response()->json(['status'=>"subcategory added successfully",'data'=>$subsubcategory],200);
        
        
    }
    public function show($id)
    {
        
        $category=category::with('subcategories.subsubcategories')->find($id);
        if(!$category)
            {
                return response()->json(["Error"=>"category not found"],400);
            }
            return response()->json(['data' => $category],201);
        }
        
        public function searchUsingCategory($id)
        {
            
            $product=product::where('category_id',$id)->get();
            if($product->count()==0)
            {
                return response()->json(['Error' => "no data is found"],401);

            }
            return response()->json(['data' => $product],201);
    }
        public function searchUsingSubCategory($id)
        {
            
            $product=product::where('subcategory_id',$id)->get();

            
            if($product->count()==0)
            {
                return response()->json(['Error' => "no data is found"],401);

            }
            return response()->json(['data' => $product],201);
    }
        public function searchUsingSubsubCategory($id)
        {
            
            $product=product::where('subsubcategory_id',$id)->get();

            
            if($product->count()==0)
            {
                return response()->json(['Error' => "no data is found"],401);

            }
            return response()->json(['data' => $product],201);
    }
}