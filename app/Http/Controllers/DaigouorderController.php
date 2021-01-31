<?php

namespace App\Http\Controllers;

use App\Models\Daigouorder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;


class DaigouorderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //回傳該用戶的所有代購單
        $Daigouorders = Daigouorder::where("uuid", $request->user()->uuid)->get();
        foreach ($Daigouorders as $value) {
            $value->cr_at = Carbon::parse($value->created_at)->diffForHumans();
        }
        return $Daigouorders;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $results = Daigouorder::count();
        $createData = Daigouorder::Create( //存到資料庫
            [
                'dgid'=>"DG" . date("Y") . date("m") . date("d") . date("H") . date("i") . ($results + 1),
                'uuid'=>$request->user()->uuid,
                'ooid'=>"null",
                'status'=>0, //尚未成交
            ]
            );
        
        'App\Models\Daigouitem'::create( //存到資料庫
            [
            'dgid' => $createData['dgid'],
            'dgurl' => "https://26seoul.com/web/#/blog/4",
            'dgtype' =>"韓國運費(若無運費，審核後會退回)",
            'count' => 1,
            'price' => 0,
            'note' => "若該訂單並無韓國運費，審核後會退回60元",
            'total'=>60,
            ]
        );
        return response()->json([
            'success' => true,
            'data' => $createData,
        ], 200);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($dgid)
    {
        $oldData = Daigouorder::where('dgid', $dgid)->first();
        if($oldData){
            Daigouorder::where('dgid', $dgid)->delete();
            return response()->json([
                                    'success' => true,
                                    'data' => $oldData,
                                    ], 200);
        }else{
            return response()->json([
                                    'success' => false,
                                    'data' => "無資料",
                                    ], 200);
        }
    }
}
