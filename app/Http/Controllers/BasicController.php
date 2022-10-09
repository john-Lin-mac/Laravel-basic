<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//引入DB，資料庫功能
use DB;
//引入Validator，表單驗證
use Validator;
//引入字串功能
use Illuminate\Support\Str;
//引入ImageManager，圖片功能
use Image;

//選單模組
use App\Models\basic\SelectModel;
//連結模組
use App\Models\basic\UrlModel;
//上傳模組
use App\Models\basic\UpModel;
//圖片模組
use App\Models\basic\ImageModel;
//圖片模組
use App\Models\basic\FileModel;

class BasicController extends Controller
{
    /**
     *
     * 首頁
     */
    public function home()
    {
        return view('home');
    }

    /**
     *
     * 選單用法
     */
    public function select()
    {
        $select_easy = new SelectModel();

        //代入參數:資料表，主健，查詢欄位
        $select_easy->select_search_all('select','id',array('id','name'));

        //查詢欄位，顯示跟值
        $select_easy->select_data_change('name','id');

        //輸出變數，選單名稱
        $data['select_easy'] = $select_easy->select_menu('select_easy');

        // ========================================================================

        $select_sql = new SelectModel();

        //代入sql語法
        $select_sql->select_search_all_sql('SELECT `id`,`name` FROM `select` ORDER BY `id` ASC');
        // 注意sql注入攻擊
        // $select_sql->select_search_all_sql('SELECT `id`,`name` FROM `select` WHERE `id` = :id ORDER BY `id` ASC',array('id' => '1'));

        //查詢欄位，顯示跟值
        $select_sql->select_data_change('name','id');

        //輸出變數，選單名稱，選單標題
        $data['select_sql'] = $select_sql->select_menu('select_sql',array('name' => '選擇名字'));

        // ========================================================================

        $select_builder = new SelectModel();

        //輸入Query Builder
        $builder = DB::table('select')->select('id','name')->orderBy('id')->get()->toArray();

        $select_builder->select_search_all_builder($builder);

        $select_builder->select_data_change('name','id');

        //輸出變數，選單名稱，選單標題，選取預設值
        $data['select_builder'] = $select_builder->select_menu('select_builder',array('name' => '選擇名字','value' => '2'));

        // ========================================================================

        $select_data = new SelectModel();

        //自訂資料
        $select_data->select_data_input(array('1' => 'john','2' => 'mary'));

        //加入屬性 class = "style"
        $data['select_data'] = $select_data->select_menu('test',array('name' => '選擇名字','value' => '1'),'class = "style"');

        return view('select',$data);
    }

    /**
     *
     * 連結用法
     */
    public function url()
    {
        //產生字串連結
        $data['url_str'] = UrlModel::url_str(['name' => 'name','type' => 'type']);

        //產生陣列連結，開頭預設?可以自訂成&
        $data['url_array'] = UrlModel::url_array(['name' => 'name','type' => 'type'],'?');

        return view('url',$data);
    }

    /**
     * 上傳
     */
    public function upload()
    {
        $data['file_all'] = DB::table('file')->select('id','file_url')->get()->toArray();

        return view('upload',$data);
    }

    /**
     * 上傳檔案
     */
    public function upload_save(Request $request)
    {
        $input_data = $request->all();

        $validator = Validator::make($input_data,
            [
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ],
            [
                'file.required' => '必須上傳檔案!!',
                'file.image' => '上傳格式必須為圖片!!',
                'file.mimes' => '圖片格式：jpeg,png,jpg,gif',
                'file.max' => '上傳最大容量為2MB!!'
            ]
        );

        if ($validator->fails()) {

            $messages = $validator->messages();

            return redirect('/basic/upload')
                    ->withInput()
                    ->withErrors($validator);

        }else{
            if($request->isMethod('post')){
                $up = new UpModel();

                //設定路徑跟檔案名稱
                $up->set_upload('images','file');

                //執行上傳
                $info = $up->up_load();

                if(isset($info['success'])){

                    DB::table('file')->insert([
                        'id' => Str::random(40),
                        'file_url' => $info['fileName'],
                    ]);

                    $request->session()->flash('message', "Upload OK");
                    return redirect('/basic/upload');
                }else{
                    $request->session()->flash('message', "File not Upload");
                    return redirect('/basic/upload');
                }
            }
        }
    }

    /**
     * 檔案 下載
     */
    public function file_download(Request $request)
    {
        if($request->has('fileName')){
            $fileName = $request->input('fileName');
        }else{
            return false;
        }

        $up = new UpModel();

        //上傳套件的下載功能
        return $up->download($fileName,'images',$fileName);
    }

    /**
     * 檔案 顯示圖片
     */
    public function file_imgShow($fileName,Request $request)
    {
        $img = new ImageModel();

        $img->set_path('public/images/');

        if($request->has('width')){
            $width = $request->input('width');
            $height = $request->input('height');
        }else{
            return false;
        }

        return $img->show($fileName,$width,$height);
    }

    /**
     * 檔案 刪除
     */
    public function file_delete(Request $request)
    {
        $input_data = $request->all();

        $validator = Validator::make($input_data,
            [
                'id' => 'required|string|max:40|exists:file,id'
            ],
            [
                'id.required' => 'id不能空白!!',
                'id.integer' => 'id必須為字串!!',
                'id.max' => '最大字元為40!!',
                'id.exists' => 'id必須存在資料庫!!'
            ]
        );

        if ($validator->fails()) {
            $request->session()->flash('message', "id驗證失敗!!");

            return response()->json([
                'status' => '0','message' => 'id驗證失敗'
            ]);
        }else{
            if($request->isMethod('delete') && $request->has('id')){

                $id = clean($request->input('id'));

                $file = new FileModel();

                //輸入Query Builder
                $delete_builder = DB::table('file')->select('file_url')->where('id',$id)->first();
                //設定路徑跟刪除欄位
                $file->set_DataBuilder('images',$delete_builder,'file_url');

                //執行刪除檔案
                $successFile = $file->file_delete();

                if($successFile){

                    DB::table('file')->where('id',$id)->delete();

                    $request->session()->flash('message', "檔案刪除成功");

                    return response()->json([
                        'status' => '1','message' => '檔案刪除成功'
                    ]);
                }else{
                    $request->session()->flash('message', "檔案刪除失敗");

                    return response()->json([
                        'status' => '0','message' => '檔案刪除失敗'
                    ]);
                }
            }
        }
    }


}
