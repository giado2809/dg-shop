@extends('layouts.admins.layout-admin')

@section('title', 'order')

@section('content')
  <main class="app-content">
    <div class="app-title">
      <ul class="app-breadcrumb breadcrumb side">
        <li class="breadcrumb-item active"><a href="#"><b>Danh sách đơn hàng</b></a></li>
      </ul>
      <div id="clock"></div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          <div class="tile-body">

            <div class="row element-button">
              <div class="col-sm-2">
                <a class="btn btn-delete btn-sm print-file" type="button" title="In" onclick="myApp.printTable()"><i
                  class="fas fa-print"></i> In dữ liệu
                </a>
              </div>
              <div class="col-sm-4">
                <form method="GET" action="{{ route('admin.order.index') }}" class="d-flex align-items-center gap-2">
                  <label class="mb-0 mr-2"><strong>Lọc theo trạng thái:</strong></label>
                  <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Tất cả --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang giao hàng</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã huỷ</option>
                  </select>
                </form>
              </div>
            </div>
            
            <table class="table table-hover table-bordered" id="sampleTable">
              <thead>
                <tr>
                  <th width="10"><input type="checkbox" id="all"></th>
                  <th>ID đơn hàng</th>
                  <th>Khách hàng</th>
                  <th>SĐT</th>
                  <th>Địa chỉ</th>
                  <th>Tổng tiền</th>
                  <th>Ngày đặt</th>
                  <th>Tình trạng</th>
                  <th>Tính năng</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($orders as $order)
                  <tr>
                    <td width="10"><input type="checkbox" name="check1" value="1"></td>
                    <td>{{$order->id}}</td>
                    <td>{{$order->user->name}}</td>
                    <td>{{$order->phone}}</td>
                    <td>{{$order->address}}</td>
                    <td>{{number_format($order->total_price, 0, ',', '.')}}đ</td>
                    <td>{{$order->created_at->format('d/m/Y H:i')}}</td>
                    <td>
                      <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-control form-control-sm"
                        onchange="this.form.submit()"   
                        {{ in_array($order->status, ['completed', 'cancelled']) ? 'disabled' : '' }}>
                          <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                          <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang giao hàng</option>
                          <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                          <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã huỷ</option>
                        </select>
                      </form>
                    </td>
                    <td>
                      <a class="btn btn-primary btn-sm view" href="{{route('admin.order.show', $order->id)}}" title="Xem">
                        <i class="fa fa-eye"></i>
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
