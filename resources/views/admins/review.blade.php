@extends('layouts.admins.layout-admin')

@section('title', 'Quản lý bình luận')

@section('content')
  <main class="app-content">
    <div class="app-title">
      <ul class="app-breadcrumb breadcrumb side">
        <li class="breadcrumb-item active"><a href="#"><b>Danh sách bình luận</b></a></li>
      </ul>
      <div id="clock"></div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          <div class="tile-body">

            <div class="row element-button mb-3">
              <div class="col-sm-2">
                <a class="btn btn-delete btn-sm print-file" type="button" title="In" onclick="myApp.printTable()">
                  <i class="fas fa-print"></i> In dữ liệu
                </a>
              </div>
              <div class="col-sm-4">
                <form method="GET" action="{{ route('admin.review.index') }}" class="d-flex align-items-center gap-2">
                  <label class="mb-0 mr-2"><strong>Lọc theo số sao:</strong></label>
                  <select name="rating" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Tất cả --</option>
                    @for ($i = 5; $i >= 1; $i--)
                      <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                    @endfor
                  </select>
                </form>
              </div>
            </div>

            </div>

            <table class="table table-hover table-bordered" id="sampleTable">
              <thead>
                <tr>
                  <th width="10"><input type="checkbox" id="all"></th>
                  <th>ID</th>
                  <th>Mã sản phẩm</th>
                  <th>Người dùng</th>
                  <th>Đánh giá (sao)</th>
                  <th>Bình luận</th>
                  <th>Ảnh</th>
                  <th>Ngày đánh giá</th>
                  <th>Tính năng</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($reviews as $review)
                  <tr>
                    <td><input type="checkbox" name="check1" value="{{ $review->id }}"></td>
                    <td>{{ $review->id }}</td>
                    <td>{{ $review->orderItem->product->name ?? 'Không tìm thấy sản phẩm' }}</td>
                    <td>{{ $review->user->username ?? 'Ẩn danh' }}</td>
                    <td>{{ $review->rating }} ⭐</td>
                    <td>{{ Str::limit($review->content, 50) }}</td>
                    <td>
                      @if ($review->image)
                        <img src="{{ asset('storage/' . $review->image) }}" width="80" height="80" alt="ảnh bình luận">
                      @else
                        <span>Không có</span>
                      @endif
                    </td>
                    <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                      <form action="{{ route('admin.review.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Xóa bình luận này?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                      </form>
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
