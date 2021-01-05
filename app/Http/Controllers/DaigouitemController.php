<?php

namespace App\Http\Controllers;

use App\Models\Daigouitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        foreach ($list as $item) {
            $total += $item->total;
        };

        $data = [
            'total'=>$total,
            'list'=>$list,
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
            'dgtype' => ['required'],
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
            "dgtype.required" => "代購商品類別為必填",
            "count.required" => "代購商品數量為必填",
            "price.required" => "代購商品價格為必填",
        ];
        //驗證是否正確
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // 資料驗證錯誤
            return ['success' => false, 'error_Message' => $validator->errors()->all()];
        } else {
            $KRW = 40;
            $typeprice = 30;
            $newdata = $request->all();
            $newdata['dgid'] = $dgid;
            $newdata['total'] = ceil(
                ($newdata['price'] * $newdata['count'] / $KRW)
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
            'count' => ['required'],
        ];
        // 錯的回饋
        $messages = [
            "count.required" => "數量為必填",


        ];
        //驗證是否正確
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // 資料驗證錯誤
            return ['success' => false, 'error_Message' => $validator->errors()->all()];
        } else {

            $oldData = Daigouitem::where('id', $itemid)->first();
            $count = $request->all()['count'];

            if ($oldData) {
                // 儲存
                $KRW = 40;
                $typeprice = 30;
                $temp = Daigouitem::where('id', $itemid)->first();
                $temp->count =  $count;
                $temp->total = ceil(
                    ($oldData['price'] *  $count / $KRW)
                     + $typeprice *  $count
                );
                $temp->save();

                // 回傳
                return response()->json([
                    'success' => true,
                    'data' => Daigouitem::where('id', $itemid)->first(),
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'data' => "無資料",
                ], 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($itemid)
    {
        //
        $oldData = Daigouitem::where('id', $itemid)->first();
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
