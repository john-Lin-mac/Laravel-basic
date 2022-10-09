@extends('layouts.html')

@section('content')


<div>
    <h3>選單套件使用</h3>
    <div>簡易用法：只要輸入參數，即可使用</div>
    <div>{!! $select_easy !!}</div>
    <hr>
    <div>簡易用法：只要輸入sql語法，即可使用</div>
    <div>{!! $select_sql !!}</div>
    <hr>
    <div>組合用法：輸入Query Builder，即可使用</div>
    <div>{!! $select_builder !!}</div>
    <hr>
    <div>自訂資料：即可使用</div>
    <div>{!! $select_data !!}</div>
</div>


<style type="text/css">

    .style{
        font-size: 30px;
    }

</style>



@endsection
