<?php

namespace App\Http\Controllers;

use App\Models\about;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $data=about::all();
        return response()->json(['data'=>$data],200);
   
    }
    public function create(Request $request)
    {
        $data=about::create([
            'title'=>$request->title,
            'description'=>$request->description
        ]);
        return response()->json(['status'=>'data saved successfully',$data],200);
    }
}
