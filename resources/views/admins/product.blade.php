@extends('layouts.admins.layout-admin')

@section('title', 'product')

@section('content')
  <main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Danh sách sản phẩm</b></a></li>
        </ul>
        <div id="clock"></div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          <div class="tile-body">
            <div class="row element-button">
              <div class="col-sm-2">
                <a class="btn btn-add btn-sm" href="{{route('admin.product.add')}}" title="Thêm"><i class="fas fa-plus"></i>
                  Tạo mới sản phẩm</a>
              </div>
              <div class="col-sm-2">
                <a class="btn btn-delete btn-sm print-file" type="button" title="In" onclick="myApp.printTable()"><i
                    class="fas fa-print"></i> In dữ liệu</a>
              </div>  
              <div class="col-sm-4">
                <form method="GET" action="{{ route('admin.product.index') }}" class="d-flex align-items-center gap-2">
                  <label class="mb-0 mr-2"><strong>Lọc theo danh mục:</strong></label>
                  <select name="category" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Tất cả --</option>
                    @foreach ($categories as $cat)
                      <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                      </option>
                    @endforeach
                  </select>
                </form>
              </div>
       
            </div>
            <form id="bulk-delete-form" action="{{ route('admin.product.bulkDelete') }}" method="POST">
              @csrf
              @method('DELETE')
              <!-- input ẩn chứa các ID được chọn -->
              <input type="hidden" name="ids" id="selected-ids">
              <table class="table table-hover table-bordered" id="sampleTable">
                  <thead>
                      <tr>
                          <th width="10"><input type="checkbox" id="check-all"></th>
                          <th>ID</th>
                          <th>Tên sản phẩm</th>
                          <th>Ảnh</th>
                          <th>Danh mục</th>
                          <th>Giá</th>
                          <th>Giá khuyến mãi</th>
                          <th>Số lượng</th>
                          <th>Chức năng</th>
                      </tr>
                  </thead>
                  <tbody>
                    @foreach ($products as $product)
                      <tr>
                          <td width="10"><input type="checkbox" class="item-checkbox" value="{{ $product->id }}"></td>
                          <td>{{ $product->id }}</td>
                          <td>{{ Str::limit($product->name, 50) }}</td>
                          <td><img src="{{ asset('storage/' . $product->image) }}" width="100" alt="{{ $product->name }}"></td>
                          <td>{{ $product->category->name ?? 'Không có' }}</td>
                          <td>{{ number_format($product->price, 0, ',', '.') }}đ</td>
                          <td>
                            @if ($product->sale_price === null)
                                Không có
                            @else
                              {{ number_format($product->sale_price, 0, ',', '.') }}đ
                            @endif
                          </td>  
                          <td>{{$product->total_quantity}}</td>                       
                          <td>
                            <button type="button" class="btn btn-primary btn-sm trash delete-single"
                              data-id="{{ $product->id }}">
                              <i class="fas fa-trash-alt"></i>
                            </button>
                            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.product.detail', $product->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                          </td>
                      </tr>
                    @endforeach                           
                  </tbody>
              </table>
              <button type="submit" class="btn btn-danger mb-3">Xoá sản phẩm đã chọn</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
