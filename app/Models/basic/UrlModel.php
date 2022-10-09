<?php

namespace App\Models\basic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Response;

class UrlModel extends Model
{
    public $urlData = array();
    public $request = null;
    public $url_str = '';
    public $url_array = array();
    public $data_count = 0;

    //網址 設定
    public function url_set($data)
    {
        $request = request();

        if(!(is_array($data) && $data != null)){
            return false;
        }

        $this->urlData = array();
        $this->url_str = '';
        $this->url_array = array();
        $this->data_count = 0;

        foreach($data as $key => $val){
            if($request->has($val)){
                $$val = clean($request->input($val));
                $this->urlData[] = "{$key}={$$val}";
                $this->url_array[$key] = $$val;
            }
        }

        $this->data_count = count($this->urlData);
    }

    //網址 字串輸出
    public static function url_str($data,$parameter = '?')
    {
        $url = new UrlModel();
        $url->url_set($data);

        if($url->data_count > 0){
            $url->url_str = $parameter.implode('&',$url->urlData);
        }

        return $url->url_str;
    }

    //網址 陣列輸出
    public static function url_array($data)
    {
        $url = new UrlModel();
        $url->url_set($data);

        return $url->url_array;
    }

}
