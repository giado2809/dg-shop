@extends('layouts.admins.layout-admin')

@section('title', 'Tạo mới danh mục')

@section('content')
  <main class="app-content">
    <div class="app-title">
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.category.index')}}">Danh sách danh mục</a></li>
        <li class="breadcrumb-item">Tạo mới danh mục</li>
      </ul>
      <div id="clock"></div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          <h3 class="tile-title">Tạo mới danh mục</h3>
          <div class="tile-body">
            <form action="{{route('admin.category.store')}}" method="POST" enctype="multipart/form-data" class="row">
              @csrf
              <div class="form-group col-md-3">
                <label class="control-label">Tên danh mục</label>
                <input class="form-control" type="text" name="name" required>
                <div class="mt-2">
                    <button class="btn btn-save" type="submit">Lưu</button>
                    <a class="btn btn-cancel" href="{{route('admin.category.index')}}">Quay lại</a>
                </div>
              </div>
              <div class="form-group col-md-3">
                <label class="control-label">Ảnh danh mục</label>
                <input class="form-control" type="file" name="image">
              </div>
              <div class="form-group col-md-3">
                <label for="exampleSelect1" class="control-label">Danh mục hiện có</label>
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    @foreach ($categories as $cat)
                        <div style="border: 1px solid #ccc; padding: 8px 12px; border-radius: 6px; background: #f9f9f9;">
                            <strong>#{{ $cat->id }}</strong> - {{ $cat->name }}
                        </div>
                    @endforeach
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection