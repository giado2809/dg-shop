@extends('layouts.admins.layout-admin')

@section('title', 'Tạo mới người dùng')

@section('content')
  <main class="app-content">
    <div class="app-title">
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.user.index')}}">Danh sách người dùng</a></li>
        <li class="breadcrumb-item">Tạo mới người dùng</li>
      </ul> 
      <div id="clock"></div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          <h3 class="tile-title">Tạo mới người dùng</h3>
          <div class="tile-body">
            <div class="d-flex justify-content-center">
              <div class="col-md-6">
                <form action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf

                  <div class="form-group col-md-12">
                    <label class="control-label">Tài khoản</label>
                    <input class="form-control" type="text" name="username" required>
                    @error('username')
                      <small class="text-danger">{{ $message }}</small>
                    @enderror
                  </div>

                  <div class="form-group col-md-12">
                    <label class="control-label">Họ tên</label>
                    <input class="form-control" type="text" name="name" required>
                  </div>
                  <div class="form-group col-md-12">
                      <label class="control-label">Ảnh đại diện</label>
                      <input class="form-control-file" type="file" name="image" accept="image/*">
                  </div>
                  <div class="form-group col-md-12">
                    <label class="control-label">Email</label>
                    <input class="form-control" type="email" name="email" required>
                  </div>

                  <div class="form-group col-md-12">
                    <label class="control-label">Số điện thoại</label>
                    <input class="form-control" type="text" name="phone" required>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="exampleSelect1" class="control-label">Phân quyền</label>
                    <select class="form-control" id="exampleSelect1" name="role">        
                      <option value="user">User</option>
                      <option value="admin">Admin</option>
                    </select>
                  </div>

                  <div class="form-group col-md-12">
                    <label class="control-label">Mật khẩu</label>
                    <input class="form-control" type="password" name="password" required>
                  </div>

                  <div class="form-group col-md-12">
                    <label class="control-label">Nhập lại mật khẩu</label>
                    <input class="form-control" type="password" name="password_confirmation" required>
                  </div>

                  <div class="form-group col-md-12">
                    <button class="btn btn-save" type="submit">Lưu</button>
                    <a class="btn btn-cancel" href="{{route('admin.user.index')}}">Quay lại</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
