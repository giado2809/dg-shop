@extends('layouts.admins.layout-admin')

@section('title', 'order')

@section('content')
  <main class="app-content">
    <div class="app-title">
      <ul class="app-breadcrumb breadcrumb side">
        <li class="breadcrumb-item active"><a href="{{route('admin.order.index')}}"><b>Danh sách đơn hàng</b></a></li>
        <li class="breadcrumb-item active"><a href="#"><b>Chi tiết đơn hàng</b></a></li>
      </ul>
      <div id="clock"></div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          <div class="tile-body">
            <div class="row element-button">
              <div class="col-sm-2">
                <a class="btn btn-add btn-sm" href="{{route('admin.order.index')}}">Quay lại</a>
              </div>
              <div class="col-sm-2">
                <a class="btn btn-delete btn-sm print-file" type="button" title="In" onclick="myApp.printTable()"><i
                    class="fas fa-print"></i> In dữ liệu</a>
              </div>
            </div>

            <h4>Thông tin nhận hàng</h4>
            <p>Họ tên: {{ $order->name }}</p>
            <p>Số điện thoại: {{ $order->phone }}</p>
            <p>Email: {{ $order->email }}</p>
            <p>Địa chỉ: {{ $order->address }}</p>
            <p>Tổng tiền: {{ number_format($order->total_price, 0, ',', '.') }}đ</p>

            <h4>Sản phẩm đã đặt</h4>
            <table class="table table-hover table-bordered" id="sampleTable">
              <thead>
                <tr>
                  <th>STT</th>
                  <th>Ảnh</th>
                  <th>Tên</th>
                  <th>Màu</th>
                  <th>Size</th>
                  <th>Đơn giá</th>
                  <th>Số lượng</th>
                  <th>Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($order->items as $item)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                      @if ($item->color && $item->color->image)
                        <img src="{{ asset('storage/' . $item->color->image) }}" width="100" alt="{{ $item->product->name }}">
                      @else
                        <img src="{{ asset('storage/' . $item->product->image) }}" width="100" alt="{{ $item->product->name }}">
                      @endif
                    </td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->color->color ?? 'N/A' }}</td>
                    <td>{{ $item->size ?? 'N/A' }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            <div class="row mt-3">
              <div class="col-md-6">
                <strong>Trạng thái đơn hàng:&nbsp;</strong>
                @if ($order->status == 'pending')
                  <span class="badge bg-warning">Chờ xác nhận</span>
                @elseif ($order->status == 'processing')
                  <span class="badge bg-info text-dark">Đang giao hàng</span>
                @elseif ($order->status == 'completed')
                  <span class="badge bg-success">Hoàn thành</span>
                @elseif ($order->status == 'cancelled')
                  <span class="badge bg-danger">Đã huỷ</span>
                @endif
              </div>
              <div class="col-md-6 text-right">
                <h5>Tổng tiền: <strong>{{ number_format($order->total_price, 0, ',', '.') }}đ</strong></h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection