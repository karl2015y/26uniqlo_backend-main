<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Daigouparameter;
use Illuminate\Support\Facades\Validator;


class DaigouparameterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Daigouparameter::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // 規則
        $rules = [
            'name' => ['required'],
            'price' => ['required'],
            'unit' => ['required'],
        ];
        // 錯的回饋
        $messages = [
            "name.required" => "名稱為必填",
            "price.required" => "價錢為必填",
            "unit.required" => "單位為必填",

        ];
        //驗證是否正確
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // 資料驗證錯誤
            return ['success' => false, 'error_Message' => $validator->errors()->all()];
        } else {
            return response()->json([
                'success' => true,
                'data' => Daigouparameter::firstOrCreate( //存到資料庫
                    ['name' => $request->all()['name']],
                    $request->all()
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
    public function update(Request $request, $id)
    {
                //更新
                // 規則
                $rules = [
                    'name' => ['required'],
                    'price' => ['required'],
                    'unit' => ['required'],
                ];
                // 錯的回饋
                $messages = [
                    "name.required" => "名稱為必填",
                    "price.required" => "價錢為必填",
                    "unit.required" => "單位為必填",

                ];
                //驗證是否正確
                $validator = Validator::make($request->all(), $rules,  $messages);
        
                if ($validator->fails()) {
                    // 資料驗證錯誤
                    return ['success' => false, 'error_Message' => $validator->errors()->all()];
                }else{
        
        
                    $oldData = Daigouparameter::find($id);
                    $newData = $request->all();
        

                    if($oldData){
                        // 儲存
                        Daigouparameter::where('id', $id)->update($newData);
                        // 回傳  
                        return response()->json([
                                                'success' => true,
                                                'data' => Daigouparameter::where('id', $id)->first()
                                                ], 200);  
                    }else{
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
    public function destroy($id)
    {

        $oldData = Daigouparameter::find($id);
        if($oldData){
            Daigouparameter::where('id', $id)->delete();
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
