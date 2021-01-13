<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Image;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAll()
    {
        // 每頁資料量
        $row_per_page = 10;

        // 撈取商品分頁資料
        $Blogs = 'App\Models\Blog'::OrderBy('updated_at', 'desc')
            ->paginate($row_per_page);

        // 設定商品圖片網址
        // foreach ($Blogs as &$blog) {
        //     if (!is_null($blog->bimg)) {
        //         // 設定商品照片網址
        //         $Product->img = url($Product->pimg);
        //     }
        // }
        foreach ($Blogs as $blog) {
            $blog->up_at = Carbon::parse($blog->updated_at)->diffForHumans();
        }

        return response()->json([
            'success' => true,
            'blog_list' => $Blogs,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 每頁資料量
        $row_per_page = 10;

        // 撈取商品分頁資料
        $Blogs = 'App\Models\Blog'::where('status',1)->where('id','<>',1)->OrderBy('updated_at', 'desc')
            ->paginate($row_per_page);

        // 設定商品圖片網址
        // foreach ($Blogs as &$blog) {
        //     if (!is_null($blog->bimg)) {
        //         // 設定商品照片網址
        //         $Product->img = url($Product->pimg);
        //     }
        // }
        foreach ($Blogs as $blog) {
            $blog->up_at = Carbon::parse($blog->updated_at)->diffForHumans();
        }

        return response()->json([
            'success' => true,
            'blog_list' => $Blogs,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 規則
        $rules = [
            'name' => ['required'],
            'bimg' => ['active_url'],
            'description' => ['required'],
            'picfile' => ['image'],
        ];
        // 錯的回饋
        $messages = [
            "name.required" => "品牌標題為必填",
            "bimg.active_url" => "品牌連結錯誤",
            "description.required" => "品牌說明為必填",
            "picfile.image" => "品牌圖片上傳格式錯誤",
        ];
        //驗證是否正確
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // 資料驗證錯誤
            return ['success' => false, 'error_Message' => $validator->errors()->all()];
        } else {
            $res = [];
            if ($request->name) {$res['name'] = $request->name;};
            if ($request->name) {$res['bimg'] = $request->bimg;};
            if ($request->name) {$res['description'] = $request->description;};
            if ($request->name) {$res['status'] = 0;};
            return response()->json([
                'success' => true,
                'data' => 'App\Models\Blog'::firstOrCreate( //存到資料庫
                    ['name' => $request->all()['name']], $res
                ),
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $blog = 'App\Models\Blog'::where("id", $id)->first();
        if ($blog) {
            $blog->up_at = Carbon::parse($blog->updated_at)->diffForHumans();
            return $blog;
        } else {
            return response()->json([
                'success' => false,
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
        // 規則
        $rules = [
            'name' => [],
            'bimg' => ['active_url'],
            'description' => [],
            'picfile' => ['image'],
        ];
        // 錯的回饋
        $messages = [
            "name.required" => "品牌標題為必填",
            "bimg.active_url" => "品牌連結錯誤",
            "description.required" => "品牌說明為必填",
            "picfile.image" => "品牌圖片上傳格式錯誤",
        ];
        //驗證是否正確
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // 資料驗證錯誤
            return ['success' => false, 'error_Message' => $validator->errors()->all()];
        } else {
            $oldData = 'App\Models\Blog'::find($id);
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
                    $file_relative_path = 'images/blog/' . $file_name;
                    // 檔案存放目錄為對外公開 public 目錄下的相對位置
                    $file_path = public_path($file_relative_path);
                    // 裁切圖片
                    // $image = Image::make($photo)->fit(450, 300)->save($file_path);
                    $image = Image::make($photo)->save($file_path);
                    // 設定圖片檔案相對位置
                    $newData['picfile'] = $file_relative_path;
                    // 商品資料更新
                    $newData['bimg'] = url($newData['picfile']);
                    // 刪除相對位址
                    unset($newData['picfile']);
                    // 設定路經
                    // $Product["pimg"] = url($Product["pimg"]);
                }
            }

            $newData_final = [];
            if (isset($newData['name'])) {$newData_final['name'] = $newData['name'];};
            if (isset($newData['status'])) {$newData_final['status'] = $newData['status'];};
            if (isset($newData['bimg'])) {$newData_final['bimg'] = $newData['bimg'];};
            if (isset($newData['description'])) {$newData_final['description'] = $newData['description'];};
            if ($oldData) {
                // 儲存
                'App\Models\Blog'::where('id', $id)->update($newData_final);
                // 回傳
                return response()->json([
                    'success' => true,
                    'data' => 'App\Models\Blog'::where('id', $id)->first(),
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
    public function destroy($id)
    {
        $oldData = 'App\Models\Blog'::find($id);
        if ($oldData) {
            'App\Models\Blog'::where('id', $id)->delete();
            return response()->json([
                'success' => true,
                'data' => $oldData,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => "無資料",
            ], 200);
        }
    }
}
