<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Faq;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\Faq\CreateRequest;
use App\Http\Requests\Admin\Faq\UpdateRequest;
class FaqController extends Controller
{
    public function index(Request $request){
        try{
            $faqs = Faq::when($request->query('faq'),function($query)use($request){
                $query->where('question','LIKE','%'.$request->query('faq').'%');
            })->orderByRaw('coalesce(cast(order_id as signed),id)')->get();
            return view('admin.pages.faq.list',compact('faqs'));
        }catch(\Exception $e){
            return back()->with('error','failed');
        }
    }
    
    public function store(CreateRequest $request){
        try{
            Db::beginTransaction();
            $maxOrderId=Faq::max('order_id');
            
                $faq=Faq::create([
                    'answer'=>$request->answer,
                    'question'=>$request->question,
                    'order_id'=>++$maxOrderId
                ]);
            Db::commit();

            return response()->json([
                'data'=>$faq,
                'message'=>__('messages.faq.created'),
                'error'=>null
            ],201);
        }catch(\Exception $e){
            Db::rollback();
            return response()->json([
                'message'=>__('messages.faq.create_error'),
                'error'=>$e->getMessage(),
                'data'=>[]
            ],$e->getCode());
        }
    }

    public function destroy($faq,Request $request){
        try{
            Db::beginTransaction();
                $faq=Faq::destroy($faq);
            Db::commit();

            return response()->json([
                'data'=>$faq,
                'message'=>__('messages.faq.deleted'),
                'error'=>null
            ],201);
        }catch(\Exception $e){
            Db::rollback();
            return response()->json([
                'message'=>__('messages.faq.delete_error'),
                'error'=>$e->getMessage(),
                'data'=>[]
            ],$e->getCode());
        }
    }

    public function update($faq,UpdateRequest $request){
        try{
            Db::beginTransaction();
            $faq=Faq::find($faq)->update($request->validated());
            Db::commit();
            return response()->json([
                'data'=>$faq,
                'message'=>__('messages.faq.updated'),
                'error'=>null
            ],201);
        }catch(\Exception $e){
            Db::rollback();
            return response()->json([
                'message'=>__('messages.faq.update_error'),
                'error'=>$e->getMessage(),
                'data'=>[]
            ],$e->getCode());
        }
    }

    public function reorder(Request $request){
        try{
            Db::beginTransaction();
            
            $neworder=$request->newOrderIds;
            sort($neworder);
            foreach($request->newOrder as $index=>$order){
                Faq::find($order)->update([
                    'order_id'=>(int)$neworder[$index]
                ]);
            }
            Db::commit();
            return response()->json([
                'data'=>[],
                'message'=>__('messages.faq.updated'),
                'error'=>null
            ],201);
        }catch(\Exception $e){
            Db::rollback();
            return response()->json([
                'message'=>__('messages.faq.update_error'),
                'error'=>$e->getMessage(),
                'data'=>[]
            ],$e->getCode());
        }
    }
}
