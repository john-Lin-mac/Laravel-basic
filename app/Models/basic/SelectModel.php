<?php

namespace App\Models\basic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Response;
//引入DB，資料庫功能
use DB;

class SelectModel extends Model
{
    public $datas = '';
    public $select_array = array();

    /*
     *  選單查詢資料庫資料 帶入參數
     */
    public function select_search_all($_table,$_tableId,$_column = array())
    {
        if(is_array($_column) && $_column != null){
            $this->datas = DB::table($_table)->select($_column)->orderBy($_tableId)->get()->toArray();
        }else{
            $this->datas = DB::table($_table)->orderBy($_tableId)->get()->toArray();
        }

    }

    /*
     *  選單查詢資料庫資料 帶入組合
     */
    public function select_search_all_builder($_builder)
    {
        $this->datas = $_builder;
    }

    /*
     *  選單查詢資料庫資料 帶入sql
     */
    public function select_search_all_sql($sql,$arr = null)
    {
        try{
            if($arr === null){
                $this->datas = DB::select($sql);
            }else{
                $this->datas = DB::select($sql,$arr);
            }
        }catch(\Illuminate\Database\QueryException $ex){
            echo $ex->getMessage();
            exit;
        }
    }

    /*
     *  將二維資料轉換一維資料，使選單可以使用
     */
    public  function select_data_change($name,$id)
    {
        if((is_array($this->datas) || is_object($this->datas)) && $this->datas != null)
        {
            foreach ($this->datas as $key => $value)
            {
                $this->select_array[$value->$id] = $value->$name;
            }
        }
    }

    /*
     *  自訂一維資料，輸入選單使用
     */
    public  function select_data_input($array = null)
    {
        $this->select_array = null;

        if($array != null)
            $this->select_array = $array;
    }

    /*
     * 產生選單網頁格式
     */
    function select_menu($select_name,$selected = array(),$attr='')
    {
         $select = "<select name='{$select_name}' id='{$select_name}' {$attr}>\r\n";

         if(isset($selected['name']))
             $select.= "<option selected='selected' value=''>{$selected['name']}</option>\r\n";

         if(is_array($this->select_array) AND $this->select_array != null)
         {
               foreach ($this->select_array as $key => $value)
               {
                    if(isset($selected['value']))
                    {
                        $key = htmlspecialchars($key);
                        $value = htmlspecialchars($value);

                        if(is_array($selected['value']))
                        {
                            if(in_array($key,$selected['value']))
                            {
                                $select.= "<option selected='selected' value='{$key}'>{$value}</option>\r\n";
                            }
                            else
                                $select.= "<option value='{$key}'>{$value}</option>\r\n";
                        }
                        else
                        {
                            if($key == $selected['value'])
                            {
                                $select.= "<option selected='selected' value='{$key}'>{$value}</option>\r\n";
                            }
                            else
                                $select.= "<option value='{$key}'>{$value}</option>\r\n";
                        }
                    }
                    else
                    {
                        $select.= "<option value='{$key}'>{$value}</option>\r\n";
                    }
               }
         }

         $select.="</select>\r\n";

         return $select;
    }

}
