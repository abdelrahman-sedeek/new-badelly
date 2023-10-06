<?php

namespace App\Http\Controllers;

use App\Models\terms;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function index()
    {
        $data=terms::with('children')->get();
        return response()->json(['terms' => $data]);
    }
    public function create(Request $request)
    {

        $parentTitle=terms::create([
            'title' => $request->title

        ]);
     
    $childTitles = $request->input('child',[]);
    // dd($childTitles);
    $childrenData = [];
    foreach ($childTitles as $childTitle) {
        if (!empty($childTitle)) {
            $childrenData[] = [
                'title' => $childTitle['title'],
                'parent_id' => $parentTitle->id,
                // 'created_at' => Carbon::now(),
                // 'updated_at' => Carbon::now(),

            ];
        }
    }
        if(!empty($childTitle)){
            terms::insert($childrenData);
        }
    $parentWithChildren = terms::with('children')->find($parentTitle->id);
    return response()->json(['terms' => $parentWithChildren]);
    }
}
