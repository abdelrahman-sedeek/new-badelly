<?php

namespace App\Http\Controllers;

use App\Models\chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\chatValidation;

class ChatController extends Controller
{
    public function create_message(chatValidation $request,$receiver_id)
    {
        if(Auth::user()->id==$receiver_id)
        {
            return response()->json(['Error'=>'forbidden'],403);
            }
            $data=Auth::user()->chats()->create([
            'message' => $request->message,
            'sender_id'=>Auth::user()->id,
            'receiver_id'=>$receiver_id,
        ]);
        
        return response()->json(['message'=>'message sent successfully ',],200);
        
    }
    public function get_messages($id){
        
        $messageRecevied=chat::with('user')->where('sender_id',$id)->orderBy('created_at','asc')->get();
        $messageSent=chat::with('user')->where('receiver_id',$id)->orderBy('created_at','asc')->get();
        return response()->json(['messages',['message sent'=>$messageSent],['message received'=>$messageRecevied],]);
    }
    public function all_chats(){
        $chat=chat::where('receiver_id',Auth::user()->id)->orderBy('created_at','desc')->with(['user'=>function($query){
            $query->orderBy('created_at','desc'); 
        }])->get()->unique('sender_id');
        
        return response()->json(["data"=>$chat]);
    }
}
