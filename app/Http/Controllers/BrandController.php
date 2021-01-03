<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BrandController extends Controller
{
    /**
     * 顯示所有的品牌
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return 'App\Models\Brand'::all();
    }

    /**
     * 新增品牌
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //存檔
        // 規則
        $rules = [
            'name' => ['required'],
            'pic' => ['active_url'],
            'description' => ['required'],
            'picfile' => ['image']
        ];
         // 錯的回饋
        $messages = [
            "name.required" => "品牌標題為必填",
            "pic.active_url" => "品牌連結錯誤",
            "description.required" => "品牌說明為必填",
            "picfile.image" => "品牌圖片上傳格式錯誤",
        ];
        //驗證是否正確
        $validator = Validator::make($request->all(), $rules,  $messages);

        if ($validator->fails()) {
            // 資料驗證錯誤
            return ['success' => false, 'error_Message' => $validator->errors()->all()];
        }else{
            return response()->json([
                    'sucess' => true,
                    'data' => 'App\Models\Brand'::firstOrCreate(  //存到資料庫
                        ['name' => $request->all()['name']],
                         $request->all()
                    )
                    ], 200);  
        }

        
    }

    /**
     * 更新品牌資訊
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        //更新
        $rules = [
            'name' => ['required'],
            'pic' => ['active_url'],
            'description' => ['required'],
            'picfile' => ['image']
        ];
         // 錯的回饋
        $messages = [
            "name.required" => "品牌標題為必填",
            "pic.active_url" => "品牌連結錯誤",
            "description.required" => "品牌說明為必填",
            "picfile.image" => "品牌圖片上傳格式錯誤",
        ];
        //驗證是否正確
        $validator = Validator::make($request->all(), $rules,  $messages);

        if ($validator->fails()) {
            // 資料驗證錯誤
            return ['success' => false, 'error_Message' => $validator->errors()->all()];
        }else{


            $oldData = 'App\Models\Brand'::find($id);
            if($oldData){
                // 儲存
                'App\Models\Brand'::where('id', $id)->update($request->all());
                // 回傳  
                return response()->json([
                                        'sucess' => true,
                                        'data' => 'App\Models\Brand'::where('id', $id)->get()
                                        ], 200);  
            }else{
                return response()->json([
                                        'sucess' => false,
                                        'data' => "無資料",
                                        ], 200);

            }

            
        }
    }

    /**
     * 刪除品牌
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $oldData = 'App\Models\Brand'::find($id);
        if($oldData){
            'App\Models\Brand'::where('id', $id)->delete();
            return response()->json([
                                    'sucess' => true,
                                    'data' => $oldData,
                                    ], 200);
        }else{
            return response()->json([
                                    'sucess' => false,
                                    'data' => "無資料",
                                    ], 200);
        }
          
    }


      /**
     * 顯示品牌底下所有的商品
     * 
     * @param  text  $brandname
     * @return \Illuminate\Http\Response
     */
    public function findProduct($brandname)
    {
        //
        $brand_num = 'App\Models\Brand'::where("name", $brandname)->count(); //符合的品牌數量
        if($brand_num<1){
            return response()->json([
                                    'sucess' => false,
                                    'data' => "無資料",
                                    ], 200);
        }else{
            $products =  'App\Models\Product'::where('category',$brandname);
            return response()->json([
                                    'sucess' => true,
                                    'lenght' => $products->count(),
                                    'data' =>   $products->get(),
                                    ], 200);
            
        }
    }
}
