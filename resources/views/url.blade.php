@extends('layouts.html')

@section('content')


<h3>連結套件使用方法</h3>

<form name="form" method="get" action="{{ url('/basic/url') }}">

    <div>名稱：<input type="text" id="name" name="name"  value="你好"></div>

    <input type="hidden" name="type" value="pc">

    <button type="submit" >送出</button>

</form>

<hr>
<div>連結字串用法</div>
<div><a href="{{route('url').$url_str}}">連結字串</a></div>
<hr>
<div>連結陣列用法</div>
<div><a href="{{route('url',$url_array)}}">連結陣列</a></div>


@endsection
