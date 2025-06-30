@extends('layouts.admins.layout-admin')

@section('title', 'Sửa danh mục')

@section('content')
  <main class="app-content">
    <div class="app-title">
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.category.index')}}">Danh sách danh mục</a></li>
        <li class="breadcrumb-item">Sửa danh mục</li>
      </ul>
      <div id="clock"></div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          <h3 class="tile-title">Sửa danh mục</h3>
          <div class="tile-body">
            <form action="{{ route('admin.category.update', $categories->id) }}" method="POST" enctype="multipart/form-data" class="row">
              @csrf
              @method('PUT')
              <div class="form-group col-md-3">
                <label class="control-label">Tên danh mục</label>
                <input class="form-control" type="text" name="name" value="{{$categories->name}}" required>
              </div>
              <div class="form-group col-md-3">
                <label class="control-label">Ảnh danh mục</label>
                <input type="file" name="image" class="form-control">
                
                @if ($categories->image)
                  <div class="mt-2">
                    <img src="{{ asset('storage/' . $categories->image) }}" width="100" alt="Ảnh hiện tại">
                  </div>
                @endif
              </div>
              <div class="form-group col-md-12">
                <button class="btn btn-save" type="submit">Lưu</button>
                <a class="btn btn-cancel" href="{{route('admin.category.index')}}">Quay lại</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

@endsection

