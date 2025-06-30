@extends('layouts.shops.layout')

@section('title', 'Order')

@section('content')
    
    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <div class="row">
                    <div class="col-lg-12"> {{-- full width cho đẹp --}}
                        <form action="{{ route('order.index') }}" method="GET" class="mb-4">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <label for="status" class="fw-bold me-2">Lọc theo trạng thái:</label>
                                <select name="status" id="status" class="form-select w-auto" onchange="this.form.submit()">
                                    <option value="">Tất cả</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang giao hàng</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã huỷ</option>
                                </select>
                            </div>
                        </form>

                        @foreach ($orders as $order)
                            <div class="card mb-4 shadow-sm p-3 rounded">
                                <h5>Đơn hàng #{{ $order->id }}</h5>
                                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
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

                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
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
                                    <div class="text-end small">
                                        <div>
                                            <strong>Tổng phụ:</strong> {{ number_format($order->total_price + $order->discount_amount, 0, ',', '.') }}đ
                                        </div>

                                        @if ($order->voucher_code)
                                            <div>
                                                <strong>Giảm ({{ $order->voucher_code }}):</strong> <span class="text-danger">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
                                            </div>
                                        @endif

                                        <div>
                                            <strong>Thành tiền:</strong> <span class="text-primary fw-bold">{{ number_format($order->total_price, 0, ',', '.') }}đ</span>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('order.detail', $order->id) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i> Xem chi tiết
                                        </a>
                                        @if ($order->status == 'pending')
                                            <form action="{{ route('order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn huỷ đơn này?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-times"></i> Huỷ mua
                                                </button>
                                            </form>
                                        @endif
                                        @if ($order->status == 'completed')
                                            @if (!$order->has_review)
                                                <a href="{{ route('review.create', $order->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fa fa-star"></i> Đánh giá
                                                </a>
                                            @else
                                                <a href="{{ route('review.show', $order->id) }}" class="btn btn-sm btn-success">
                                                    <i class="fa fa-comments"></i> Xem đánh giá
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Checkout Section End -->
    
@endsection

