@extends('layouts.html')

@section('content')


@if(count($errors) > 0)
    驗證錯誤訊息：
    <div>
    @foreach ($errors->all() as $error)
        {{ $error }}<br/>
    @endforeach
    </div>
    <br/>
@endif

@if (Session::has('message'))
   <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

@if(count($file_all) > 0)
    <h3>圖片套件使用</h3>
    <table border="1" width="500px">
        <tr>
            <td width="30%" align="center">圖片</td><td align="center" width="30%">下載</td><td align="center" width="40%">刪除</td>
        </tr>
    @foreach ($file_all as $val)
        <tr>
            <td width="30%">
                <img src="{{route('file.imgShow',['fileName' => $val->file_url,'width' => '200'])}}"></img>
            </td>
            <td width="30%" align="center">
                <a href="{{route('file.download',['fileName' => $val->file_url])}}">下載<a/>
            </td>
            <td width="40%" align="center">
                <a class="a_delect" href="">
                    刪除
                    <input type="hidden" name="id" id="id" value="{{$val->id}}">
                </a>
            </td>
        </tr>
    @endforeach
    </table>
@endif

<h3>上傳套件使用</h3>
<form name="form" method="post" enctype="multipart/form-data" action="{{ url('/basic/upload_save') }}">
    {{ csrf_field() }}

    <input type="file" name="file">

    <p/>
    <div>
        <button type="submit" >上傳</button>
    </div>

</form>

<script>
    $('.a_delect').click(function(e){
        e.preventDefault();
        var data = {};
        var _id = $(this).find('#id').val();

        $.ajax({
            url: "{{route('file.delete')}}",
            type: 'POST',
            dataType: "json",
            data: {_method: 'delete', id: _id,_token: '{{csrf_token()}}'},
            success: function(data){
                console.log(data.status);
                console.log(data.message);
            },
            error: function (){}
        }).done(function (data) {
            window.location.replace("{!!route('upload')!!}");
        }).fail(function (jqXHR, textStatus) {
            window.location.reload();
        });
    });
</script>

@endsection
