@extends('layouts.admins.layout-admin')

@section('title', 'Sửa thông tin người dùng')

@section('content')
<main class="app-content">
  <div class="app-title">
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Danh sách người dùng</a></li>
      <li class="breadcrumb-item">Sửa người dùng</li>
    </ul>
    <div id="clock"></div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <h3 class="tile-title">Sửa người dùng</h3>
        <div class="tile-body">
          <div class="d-flex justify-content-center">
            <div class="col-md-6">
              <form action="{{ route('admin.user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group col-md-12">
                  <label class="control-label">Tài khoản</label>
                  <input class="form-control" type="text" name="username" value="{{ $user->username }}" disabled>
                </div>

                <div class="form-group col-md-12">
                  <label class="control-label">Họ tên</label>
                  <input class="form-control" type="text" name="name" value="{{ $user->name }}" required>
                </div>

                <div class="form-group col-md-12">
                  <label class="control-label">Ảnh đại diện</label>
                  @if ($user->image)
                    <div class="mb-2">
                      <img src="{{ asset('storage/' . $user->image) }}" alt="Ảnh hiện tại" width="80">
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image">
                      <label class="form-check-label" for="remove_image">Xoá ảnh hiện tại</label>
                    </div>
                  @endif
                  <input class="form-control-file" type="file" name="image" accept="image/*">
                </div>

                <div class="form-group col-md-12">
                  <label class="control-label">Email</label>
                  <input class="form-control" type="email" name="email" value="{{ $user->email }}" required>
                </div>

                <div class="form-group col-md-12">
                  <label class="control-label">Số điện thoại</label>
                  <input class="form-control" type="text" name="phone" value="{{ $user->phone }}" required>
                </div>

                <div class="form-group col-md-12">
                  <label class="control-label">Phân quyền</label>
                  <select class="form-control" name="role">
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                  </select>
                </div>
                <div class="form-group col-md-12">
                  <label>
                      @if(auth()->id() !== $user->id)
                          <!-- Hiển thị nút khóa/mở -->
                          <input type="checkbox" name="is_blocked" {{ $user->is_blocked ? 'checked' : '' }}>
                          Khóa tài khoản
                      @endif   
                  </label>
                </div>
                <div class="form-group col-md-12">
                  <button class="btn btn-save" type="submit">Lưu</button>
                  <a class="btn btn-cancel" href="{{ route('admin.user.index') }}">Quay lại</a>
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
