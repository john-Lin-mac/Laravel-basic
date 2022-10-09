<?php

namespace App\Models\basic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Response;
//檔案刪除
use Illuminate\Support\Facades\File;
//引入DB，資料庫功能
use DB;

class FileModel extends Model
{
    public $path = '';
    public $datas = null;
    public $column = null;
    public $filePath = '';

     /*
     *  設定路徑 帶入參數
     */
    public function set_path($_path,$fileName)
    {
        $this->path = $_path.'/'.$fileName;
    }

    /*
     *  查詢資料庫檔案資料 帶入組合
     */
    public function set_DataBuilder($_path,$_builder,$_column)
    {
        $this->datas = $_builder;
        $this->path = $_path;
        $this->column = $_column;
    }

    /*
     *  查詢資料庫檔案資料 帶入sql
     */
    public function set_DataSql($_path,$sql,$arr = null,$_column)
    {
        $this->path = $_path;
        $this->column = $_column;
        $datas = array();

        try{
            if($arr === null){
                $datas = DB::select($sql);
            }else{
                $datas = DB::select($sql,$arr);
            }
        }catch(\Illuminate\Database\QueryException $ex){
            echo $ex->getMessage();
            exit;
        }

        $this->datas = $datas[0];
    }

    //檔案刪除
    public function file_delete()
    {
        if($this->datas !== null)
        {
            if(!($this->datas->{$this->column} !== null && $this->datas->{$this->column} != '')){
                return null;
            }

            $this->filePath = public_path($this->path.'/'.$this->datas->{$this->column});
        }else{
            if(!($this->path != '')){
                return null;
            }

            $this->filePath = public_path($this->path);
        }

        if(!File::exists($this->path)){
            return null;
        }

        $result = File::delete($this->filePath);

        return $result;
    }
}
