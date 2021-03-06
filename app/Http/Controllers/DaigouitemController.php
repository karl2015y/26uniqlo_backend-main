<?php

namespace App\Http\Controllers;

use App\Models\Daigouitem;
use App\Models\Daigouorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;


class DaigouitemController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $dgid)
    {
        //回傳該代購單下的品項
        $list = Daigouitem::where("dgid", $dgid)->get();
        $total = 0;
        $Daigouorder = Daigouorder::where("dgid", $dgid)->first();
        foreach ($list as $item) {
            $total += $item->total;
            $item->cr_at = Carbon::parse($item->created_at)->diffForHumans();
            $item->up_at = Carbon::parse($item->updated_at)->diffForHumans();
        };

        $data = [
            'total'=>$total,
            'list'=>$list,
            'daigouorder'=>$Daigouorder
        ];
        return $data;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $dgid)
    {
        // 規則
        $rules = [
            'dgurl' => ['required', 'active_url'],
            'dgimgurl' => ['active_url'],
            'picfile' => ['image'],
            'dgtypeId' => ['required'],
            'count' => ['required'],
            'price' => ['required'],
            'note' => [],
        ];
        // 錯的回饋
        $messages = [
            "dgurl.active_url" => "代購連結錯誤",
            "dgurl.required" => "代購連結為必填",
            "dgimgurl.active_url" => "代購圖片連結錯誤",
            "picfile.image" => "代購圖片上傳格式錯誤",
            "dgtypeId.required" => "代購商品類別為必填",
            "count.required" => "代購商品數量為必填",
            "price.required" => "代購商品價格為必填",
        ];
        //驗證是否正確
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // 資料驗證錯誤
            return ['success' => false, 'error_Message' => $validator->errors()->all()];
        } else {
            $newdata = $request->all();
            $newdata['dgtype'] = 'App\Models\Daigouparameter'::where("id",$newdata['dgtypeId'])->first();
            unset($newdata["dgtypeId"]);
            $KRW = 'App\Models\Daigouparameter'::where("name","韓幣")->first()->price;
            $typeprice = $newdata['dgtype']->price;
            $newdata['dgid'] = $dgid;
            $newdata['total'] = ceil(
                ($newdata['price'] / $KRW)
                 + $typeprice * $newdata['count']
            );
            return response()->json([
                'success' => true,
                'data' => Daigouitem::create( //存到資料庫
                    $newdata
                ),
            ], 200);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $itemid)
    {
        // 規則
        $rules = [
            'dgurl' => ['active_url'],
            'dgimgurl' => ['active_url'],
            'picfile' => ['image'],
            'dgtypeId' => [],
            'count' => [],
            'price' => [],
            'note' => [],
        ];
        // 錯的回饋
        $messages = [
            "dgurl.active_url" => "代購連結錯誤",
            "dgurl.required" => "代購連結為必填",
            "dgimgurl.active_url" => "代購圖片連結錯誤",
            "picfile.image" => "代購圖片上傳格式錯誤",
            "dgtypeId.required" => "代購商品類別為必填",
            "count.required" => "代購商品數量為必填",
            "price.required" => "代購商品價格為必填",
        ];
        //驗證是否正確
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // 資料驗證錯誤
            return ['success' => false, 'error_Message' => $validator->errors()->all()];
        } else {
            $newdata = $request->all();
            $newdata['dgtype'] = 'App\Models\Daigouparameter'::where("id",$newdata['dgtypeId'])->first();
            unset($newdata["dgtypeId"]);
            $KRW = 'App\Models\Daigouparameter'::where("name","韓幣")->first()->price;
            $typeprice = $newdata['dgtype']->price;
            $newdata['total'] =ceil(
                ($newdata['price'] / $KRW)
                 + $typeprice * $newdata['count']
            );
            Daigouitem::where('id', $itemid)->update($newdata);
            return response()->json([
                'success' => true,
                'data' => Daigouitem::where('id', $itemid)->first(),
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $itemid)
    {
        $oldData = Daigouitem::where('id', $itemid)->first();
        if($oldData['dgtype']=="韓國運費(若無運費，審核後會退回)" && $request->user()->roles!="admin"){
            return response()->json([
                'success' => false,
                'data' => "若無運費，審核後會退回，無法自行刪除",
                ], 200);
        }
        if($oldData){
            Daigouitem::where('id', $itemid)->delete();
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
