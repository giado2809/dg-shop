@extends('layouts.admins.layout-admin')

@section('title', 'Danh sách danh mục')

@section('content')
  <main class="app-content">
      <div class="app-title">
          <ul class="app-breadcrumb breadcrumb side">
              <li class="breadcrumb-item active"><a href="#"><b>Danh sách danh mục</b></a></li>
          </ul>
          <div id="clock"></div>
      </div>
      <div class="row">
          <div class="col-md-12">
              <div class="tile">
                <div class="tile-body">
                  <div class="row element-button">
                    <div class="col-sm-2">
                      <a class="btn btn-add btn-sm" href="{{route('admin.category.add')}}" title="Thêm"><i class="fas fa-plus"></i>
                        Tạo mới danh mục</a>
                    </div>
                    <div class="col-sm-2">
                      <a class="btn btn-delete btn-sm print-file" type="button" title="In" onclick="myApp.printTable()"><i
                          class="fas fa-print"></i> In dữ liệu</a>
                    </div>
                  </div>

                  <form id="bulk-delete-form" action="{{ route('admin.category.bulkDelete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <!-- input ẩn chứa các ID được chọn -->
                    <input type="hidden" name="ids" id="selected-ids">
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                <th width="10"><input type="checkbox" id="check-all"></th>
                                <th>ID</th>
                                <th>Tên danh mục</th>
                                <th>Ảnh</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($categories as $cat)
                            <tr>
                                <td width="10"><input type="checkbox" class="item-checkbox" value="{{ $cat->id }}"></td>
                                <td>{{ $cat->id }}</td>
                                <td>{{ $cat->name }}</td>
                                <td><img src="{{ asset('storage/' . $cat->image) }}" width="60" alt="{{ $cat->name }}"></td>
                                <td>
                                  <button type="button" class="btn btn-primary btn-sm trash delete-single"
                                    data-id="{{ $cat->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                  </button>
                                  <a href="{{ route('admin.category.edit', $cat->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                  </a>
                                </td>
                            </tr>
                          @endforeach                           
                        </tbody>
                    </table>  
                    <button type="submit" class="btn btn-danger mb-3">Xoá danh mục đã chọn</button>
                  </form>
                </div>
              </div>
          </div>
      </div>
  </main>
@endsection
