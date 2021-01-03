<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Support\Facades\File;


class BrandController extends Controller
{
    /**
     * 顯示所有的品牌
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->has('type')) {
            //
            $type = $request->input('type');
            return 'App\Models\Brand'::where("type", $type)->get();
        }else{
            return 'App\Models\Brand'::all();
        }
       
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
                    'success' => true,
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
            'name' => [],
            'pic' => ['active_url'],
            'description' => [],
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
            $newData = $request->all();

            if ($request->hasFile('picfile')) {
                // $fileName = $file->getClientOriginalName();
                if (isset($newData['picfile'])) {
                    

                    // 如果原本就有圖片，先把舊的圖片刪掉
                    if ($oldData->pic) {
                        $s = public_path(parse_url($oldData->pic)['path']);
                        if (File::exists($s)) {
                            File::delete($s);
                        }
                    }


                    // 有上傳圖片
                    $photo = $newData['picfile'];
                    // 檔案副檔名
                    $file_extension = $photo->getClientOriginalExtension();
                    // 產生自訂隨機檔案名稱
                    $file_name = uniqid() . '.' . $file_extension;
                    // 檔案相對路徑
                    $file_relative_path = 'images/brand/' . $file_name;
                    // 檔案存放目錄為對外公開 public 目錄下的相對位置
                    $file_path = public_path($file_relative_path);
                    // 裁切圖片
                    // $image = Image::make($photo)->fit(450, 300)->save($file_path);
                    $image = Image::make($photo)->save($file_path);
                    // 設定圖片檔案相對位置
                    $newData['picfile'] = $file_relative_path;
                    // 商品資料更新
                    $newData['pic'] = url($newData['picfile']);
                    // 刪除相對位址
                    unset($newData['picfile']);
                    // 設定路經
                    // $Product["pimg"] = url($Product["pimg"]);
                }
            }
            if($oldData){
                // 儲存
                'App\Models\Brand'::where('id', $id)->update($newData);
                // 回傳  
                return response()->json([
                                        'success' => true,
                                        'data' => 'App\Models\Brand'::where('id', $id)->first()
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
                                    'success' => false,
                                    'data' => "無資料",
                                    ], 200);
        }else{
            $products =  'App\Models\Product'::where('category',$brandname);
            return response()->json([
                                    'success' => true,
                                    'lenght' => $products->count(),
                                    'data' =>   $products->get(),
                                    ], 200);
            
        }
    }
}
