@extends('layouts.html')

@section('content')


<h3>好用套件使用方法</h3>

<div><a target="_blank" href="{{route('select')}}">選單套件</a></div>
<p></p>
<div><a target="_blank" href="{{route('url')}}">連結套件</a></div>
<p></p>
<div><a target="_blank" href="{{route('upload')}}">上傳，圖片，檔案套件</a></div>


@endsection
