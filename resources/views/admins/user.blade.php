@extends('layouts.admins.layout-admin')

@section('title', 'Người dùng')

@section('content')
  <main class="app-content">
    <div class="app-title">
      <ul class="app-breadcrumb breadcrumb side">
        <li class="breadcrumb-item active"><a href="#"><b>Danh sách người dùng</b></a></li>
      </ul>
      <div id="clock"></div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          <div class="tile-body">
            <div class="row element-button">
              <div class="col-sm-2">
                <a class="btn btn-add btn-sm" href="{{route('admin.user.add')}}" title="Thêm"><i class="fas fa-plus"></i>
                  Tạo mới người dùng</a>
              </div>
              <div class="col-sm-2">
                <a class="btn btn-delete btn-sm print-file" type="button" title="In" onclick="myApp.printTable()"><i
                    class="fas fa-print"></i> In dữ liệu</a>
              </div>
            </div>
            <table class="table table-hover table-bordered js-copytextarea" cellpadding="0" cellspacing="0" border="0"
              id="sampleTable">
              <thead>
                <tr>
                  <th width="10"><input type="checkbox" id="all"></th>
                  <th>ID</th>
                  <th>Tài khoản</th>
                  <th>Họ tên</th>
                  <th>Ảnh đại diện</th>
                  <th >Email</th>
                  <th>SĐT</th>
                  <th>Role</th>
                  <th>Tính năng</th>
                </tr>
              </thead>    
              <tbody>
                @foreach ($users as $user)
                  <tr>
                    <td width="10"><input type="checkbox" name="check1" value="1"></td>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username  }}</td>
                    <td>{{ $user->name }}</td>
                    <td><img src="{{ asset('storage/' . $user->image) }}" width="60" alt="avatar"></td>
                    <td>{{ $user->email  }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                      <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
