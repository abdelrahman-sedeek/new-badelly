<?php

namespace App\Http\Controllers\API;

use App\Models\product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\productResource;
use App\Models\category;
use App\Models\subcategory;
use App\Models\subsubcategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function index(){
        $product=product::get();
        return response()->json(['data' => $product],201);
    }
    public function show($id)
    {
        $product=product::find($id);
        return response()->json(['data' => $product],201);
    }
    /**
 * show all function
 */
    public function showAll()
        {
            $product = product::with('user')->where('isActive', true)->get();
            if($product->count()==0)
            {
                return response()->json(['error' => "no Data is found"],400);

            }
            // return response()->json(['data' => $product],201);
            return productResource::collection($product);
        }


/**
 * create function
 */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|required',
            'description' => 'required|string|required',
            'image1' => 'required|image',
            'image2' => 'required|image',
            'image3' => 'nullable|image',
            'image4' => 'nullable|image',
            'video' => 'nullable|file|mimetypes:video/mp4',
            'category_id' => 'required',
            'subcategory_id' => 'nullable',
            'subsubcategory_id' => 'nullable',

        ]);
        if($validator->fails())
        {
            return response()->json(["Error", $validator->errors()],400);

        }
        $imageFields = ['image1', 'image2', 'image3', 'image4'];
        $imageData = [];
                // Handle image1 update
        foreach ($imageFields as $index => $imageField) {
           
            if ($request->hasFile($imageField)) {
                $image = $request->file($imageField);
                
                    $imageName = $image->getClientOriginalName();
                    $sanitizedFileName = str_replace(' ', '_', $imageName);

                
                
                $image->move(public_path('images'), $sanitizedFileName);
        
                $imageData[$imageField] = '/images/' . $sanitizedFileName;
            }
        }
        
        $video = null;
        if ($request->hasFile('video')) {
           
            $videoReq=$request->file('video');

            $videoName=$videoReq->getClientOriginalName();
            $videoName=str_replace(' ','_',$videoName);
            $videoReq->move(public_path('videos'), $videoName);
            
           
            $video='/videos/' . $videoName;
        }
        /** handling choosing coreect subcategory and subsubcategory */
        $categoryId = $request->input('category_id');
        $subcategoryId=$request->input('subcategory_id');
        $subsubcategoryId=$request->input('subsubcategory_id');
        
            $subcategory = subcategory::with('category')->where('id',$subcategoryId)->where('category_id',$categoryId)->exists();
        
            $subsubcategory = subsubcategory::with('subcategory')->where('id',$subsubcategoryId)->where('subcategory_id',$subcategoryId)->exists();
            // dd($subsubcategory);
            if($subsubcategoryId!=null && $subcategoryId!=null)
        {
            if(!$subcategory || !$subsubcategory ){
           
                return response()->json(['Error' => 'choose correct subcategory or subsubcategory'],400);

                
            }
           
                $data=Auth::user()->products()->create([
            
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'video' =>  $video,
                    'image1' => $imageData['image1'] ?? null,
                    'image2' => $imageData['image2'] ?? $request->image2,
                    'image3' => $imageData['image3'] ?? $request->image3,
                    'image4' => $imageData['image4'] ?? $request->image4,
                    'category_id' => $categoryId,
                    'subcategory_id' => $subcategoryId,
                    'subsubcategory_id' => $subsubcategoryId,
        ]);
            
        return response()->json(['status'=>"success",'data'=>$data], 201);
}
        $data=Auth::user()->products()->create([
        
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'video' =>  $video,
            'image1' => $imageData['image1'] ?? null,
            'image2' => $imageData['image2'] ?? $request->image2,
            'image3' => $imageData['image3'] ?? $request->image3,
            'image4' => $imageData['image4'] ?? $request->image4,
            'category_id' => $categoryId,
            'subcategory_id' => $subcategoryId,
            'subsubcategory_id' => $subsubcategoryId,
        ]);


    return response()->json(['status'=>"success",'data'=>$data], 201);
    }
    public function edit($id)
        {
            $product = Product::find($id);
            if(!$product)
            {
                return response()->json(['error' => 'no product found'],400);
            }
            return response()->json(['data'=>$product],200);

        }
/**
 *                                   update function
 */
    public function update(Request $request,$id)
        {
            $product = product::find($id); 
            if(!$product)
            {
                return response()->json(['error' => 'no product found',400]);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|required',
                'description' => 'required|string|required',
                'video' => 'nullable',
                'image1' => 'required|image',
                'image2' => 'required|image',
                'image3' => 'nullable|image',
                'image4' => 'nullable|image',
                'category_id' => 'nullable|string|required',

            ]);

            if($validator->fails())
            {
                return response()->json(["Error", $validator->errors()],400);

            }
             $imageFields = ['image1', 'image2', 'image3', 'image4'];
             $imageData = [];
                // Handle image1 update
            foreach ($imageFields as $index => $imageField) {
            
                if ($request->hasFile($imageField)) {
                    $image = $request->file($imageField);
                    
                        $imageName = $image->getClientOriginalName();
                        $sanitizedFileName = str_replace(' ', '_', $imageName);

                    
                    
                    $image->move(public_path('images'), $sanitizedFileName);
            
                    $imageData[$imageField] = '/images/' . $sanitizedFileName;
                }

            }
            $video = null;
            if ($request->hasFile('video')) {
            
                $videoReq=$request->file('video');

                $videoName=$videoReq->getClientOriginalName();
                $videoName=str_replace(' ','_',$videoName);
                $videoReq->move(public_path('videos'), $videoName);
                
            
                $video='/videos/' . $videoName;
            }
           
            $data=$product->update([
    
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'video' =>  $video,
                'image1' => $imageData['image1'] ?? null,
                'image2' => $imageData['image2'] ?? $request->image2,
                'image3' => $imageData['image3'] ?? $request->image3,
                'image4' => $imageData['image4'] ?? $request->image4,
                'category_id' => $request->input('category_id'),
    
         ]);

      

            return response()->json(['message'=>"updated sucessfully",$data], 201);

        }
        public function delete($id)
        {
            $product = product::find($id);
            if(!$product)
            {
                return response()->json(['error' => 'no product found'] ,400);
            }
            $product->sofdelete();
            return response()->json(['success' =>"deleted successfully"],201);
        }
}
