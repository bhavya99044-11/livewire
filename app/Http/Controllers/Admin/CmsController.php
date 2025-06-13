<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Cms;
use App\Http\Requests\Admin\CmsRequest;

class CmsController extends Controller
{
    public function index($cms){
        try{
            $cms=Cms::where('slug',$cms)->firstOrFail();
            return view('admin.pages.cms.cms',compact('cms'));
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong');
        }

    }

    public function create($cms,Request $request){
      try{
        $cms=Cms::where('slug',$cms)->firstOrFail();
        $cms->content=$request->content;
        $cms->save();
      }catch(\Exception $e){
        return response()->json([
            'error'=>$e->getMessage(),
            'message'=>'error while creating',
            'data'=>[],
        ],$e->getCode());
      }
    }
}
