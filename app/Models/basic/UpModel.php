<?php

namespace App\Models\basic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Response;
//引入字串功能
use Illuminate\Support\Str;

class UpModel extends Model
{
    public $config = array();
    public $request = null;
    public $file = null;

     /*
     *  設定上傳
     */
    public function set_upload($_path,$fileFieldName = 'file',$encrypt = true)
    {
        $path = public_path($_path);

        if(!is_dir($path))
        {
            return false;
        }

        $this->config['fileField_name'] = $fileFieldName;
        $this->config['upload_path'] = $path;
        $this->config['encrypt_name'] = $encrypt; //加密名稱

        $this->request = request();
        $this->file = $this->request->file($this->config['fileField_name']);
    }

    /*
     *  加密檔名
     */
    public function up_encrypt_name($file = array())
    {
        $encrypt = $this->config['encrypt_name'];

        if($encrypt === true){
            if(is_object($file) && $file != null){
                $file_name = Str::random(40).'.'.$file->getClientOriginalExtension();
            }else{
                $file_name = Str::random(40).'.'.$this->file->getClientOriginalExtension();
            }

            return $file_name;

        }else{
            if(is_object($file) && $file != null){
                $file_name = $file->getClientOriginalName();
            }else{
                $file_name = $this->file->getClientOriginalName();
            }

            return $file_name;
        }
    }


    /*
     *  單一上傳圖片
     */
    public function up_load()
    {
        if(!$this->request->hasFile($this->config['fileField_name'])){
            return null;
        }

        $info = array();

        $file_path = $this->config['upload_path'];

        $file_name = $this->up_encrypt_name();

        $size = $this->file->getSize();
        $mimeType = $this->file->getMimeType();

        $success = $this->file->move($file_path,$file_name);

        $info = array(
            'success' => $success,
            'fileName' => $file_name,
            'originalName' => $this->file->getClientOriginalName(),
            'originalExtension' => $this->file->getClientOriginalExtension(),
            'size' => $size,
            'mimeType' => $mimeType,
        );

        return $info;
    }

    /*
     *  批次上傳圖片
     */
    public function up_loads()
    {
        if(!$this->request->hasFile($this->config['fileField_name'])){
            return null;
        }

        $file_path = $this->config['upload_path'];

        $info = array();

        foreach ($this->file as $key => $file) {

            $file_name = $this->up_encrypt_name($file);

            $size = $file->getSize();
            $mimeType = $file->getMimeType();

            $success = $file->move($file_path,$file_name);

            $info[$key] = array(
                'success' => $success,
                'fileName' => $file_name,
                'originalName' => $file->getClientOriginalName(),
                'originalExtension' => $file->getClientOriginalExtension(),
                'size' => $size,
                'mimeType' => $mimeType,
            );
        }

        return $info;
    }

    /*
     *  下載檔案
     */
    public function download($file_name = null,$_file_path,$file_url)
    {
        $file_path = public_path($_file_path.'/'.$file_url);

        if(!is_file($file_path)){
            return false;
        }

        //防止chorme存取快取
        return response()->download($file_path,$file_name,
        [ 'Cache-Control' => 'no-cache, no-store, must-revalidate', 'Pragma' => 'no-cache', 'Expires' => '0' ]);
    }
}
