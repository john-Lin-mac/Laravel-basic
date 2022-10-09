<?php

namespace App\Models\basic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Response;
//圖片
use Image;

class ImageModel extends Model
{
    public $path = '';
    public $savePath = '';
    public $quality = '90';

    /*
     *  設定路徑 帶入參數
     */
    public function set_path($_path)
    {
        if($_path != ''){
            $this->path = base_path().'/'.$_path;
        }else{
            $this->path = base_path().'/'.'/public/images/';
        }
    }

    /*
     *  設定儲存路徑 帶入參數
     */
    public function set_savePath($_path)
    {
        if($_path != ''){
            $this->savePath = base_path().'/'.$_path;
        }else{
            $this->savePath = base_path().'/'.'/public/images';
        }
    }

    /*
     *  顯示圖片
     */
    public function show($fileName,$_width,$_height)
    {
        try {

            if($_width > 2000 || $_height > 2000){
                throw new \Exception('圖片尺寸規格上限!');
            }

            $this->path = $this->path.$fileName;
            $img = Image::make($this->path);

            if($_height != ''){
                $img->resize($_width,$height);
            }else{
                $img->resize($_width,null,function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            return $img->response('jpg',$this->quality);
        }catch(\Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    /*
     *  裁剪圖片
     */
    public function resize($fileName,$_width)
    {
        try {

            $this->path = $this->path.'/'.$fileName;
            $this->savePath = $this->savePath.'/'.$fileName;
            $img = Image::make($this->path);

            $img->resize($_width,null,function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($this->savePath,$this->quality);

        }catch(\Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

}
