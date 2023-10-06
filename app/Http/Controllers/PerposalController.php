<?php

namespace App\Http\Controllers;


use App\Models\product;
use App\Models\perposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\PerposalCreateFormRequest;
use App\Models\access;

class PerposalController extends Controller
{
    public function create_perposal(PerposalCreateFormRequest $request,product $product)
    {
        // dd(Auth::user());
        if(Auth::id()==$product->user_id){
            return response()->json(['massage'=> "can't send proposer to yourself "],400);
        }
        $data= Auth::user()->proposals()->create([
            'title' => $request->title,
            'description' => $request->description,
            'product_id' => $product->id,
            'product_Owner' => $product->user_id,
        ]);
        return response()->json(['$data'=>$data],200);
    }
    public function get_perposal()
    {
        $id=Auth::id();

        $perposal=perposal::whereHas('product',function($query) use ($id){
            $query->where('product_Owner',$id)->where('isApproved',null);
        })->get();
        if($perposal->count()==0)
        {
            return response()->json(['error'=>'no data is found'],400);

        }
        return response()->json(['data'=>$perposal],200);
    }
    public function perposal_status(perposal  $perposal,Request $request) {
        $request->validate([
            'accept_proposal' => 'required|boolean',
        ]);
        $accepted = $request->input('accept_proposal');
        if ($accepted) {

            $perposal->update(['isApproved' => 1]);
            DB::beginTransaction();
            access::updateOrCreate([
            'perposal_id' => auth()->user()->id,
            'product_id' => $perposal->product_id,
            'status' => 1,
            ]);
           $message="proposal accepted";
           DB::commit();
        }
        else{
            $perposal->update(['isApproved' => 0]);

            $message="proposal denied";
        }
        return response()->json($message);


    }
   
}
