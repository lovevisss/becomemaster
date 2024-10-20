@extends('layouts.app')

@section('content')
123
    <form action="{{route('upload.file')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="file">选择文件</label>
            <input type="file" id="file" name="file" required>
        </div>
        <button type="submit">上传</button>
    </form>
@endsection
