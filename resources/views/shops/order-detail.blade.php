@extends('layouts.shops.layout')

@section('title', 'Chi tiết đơn hàng')

@section('content')
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow p-4 rounded">
                            <h4 class="mb-4">Chi tiết đơn hàng #{{ $order->id }}</h4>

                            <div class="mb-3">
                                <strong>Họ tên:</strong> {{ $order->name }}<br>
                                <strong>Email:</strong> {{ $order->email ?? 'Không có' }}<br>
                                <strong>Số điện thoại:</strong> {{ $order->phone }}<br>
                                <strong>Địa chỉ:</strong> {{ $order->address }}<br>
                                <strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
                                <strong>Trạng thái:</strong>
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

                            <h5 class="mb-3">Sản phẩm trong đơn hàng:</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td width="80">
                                                @php
                                                    $image = $item->color && $item->color->image
                                                            ? asset('storage/' . $item->color->image)
                                                            : asset('storage/' . $item->product->image);
                                                @endphp
                                                <img src="{{ $image }}" width="70" height="70" alt="{{ $item->product->name }}">
                                            </td>
                                            <td class="text-left">
                                                {{ $item->product->name }} <br>

                                                @if ($item->color)
                                                    <small class="text-muted">Màu: {{ $item->color->color }}</small><br>
                                                @endif

                                                @if ($item->size)
                                                    <small class="text-muted">Size: {{ $item->size }}</small>
                                                @endif
                                            </td>
                                            <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mt-4 d-flex justify-content-between align-items-start flex-wrap">
                                {{-- Nút quay lại bên trái --}}
                                <a href="{{ route('order.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Quay lại danh sách đơn hàng
                                </a>

                                {{-- Khối tổng tiền bên phải --}}
                                <div class="border rounded p-3 text-start mt-3 mt-md-0" style="min-width: 300px;">
                                    <p class="mb-1">
                                        <strong>Tổng phụ:</strong>
                                        <span class="float-end">{{ number_format($order->total_price + $order->discount_amount, 0, ',', '.') }}đ</span>
                                    </p>

                                    @if ($order->voucher_code)
                                        <p class="mb-1">
                                            <strong>Giảm giá ({{ $order->voucher_code }}):</strong>
                                            <span class="float-end text-danger">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
                                        </p>
                                    @endif

                                    <hr class="my-2">
                                    <p class="mb-0 fs-5">
                                        <strong>Tổng tiền cần thanh toán:</strong>
                                        <span class="float-end text-primary">{{ number_format($order->total_price, 0, ',', '.') }}đ</span>
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
