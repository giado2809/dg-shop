@extends('layouts.shops.layout')

@section('title', 'Đánh giá đơn hàng')

@section('content')
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow p-4 rounded">
                            <h4 class="mb-4">Đánh giá đơn hàng #{{ $order->id }}</h4>

                            <div class="mb-4">
                                <h6 class="text-primary">Thông tin người nhận:</h6>
                                <p><strong>Họ tên:</strong> {{ $order->name }}</p>
                                <p><strong>Email:</strong> {{ $order->email ?? 'Không có' }}</p>
                                <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                                <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                                <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            <form action="{{ route('review.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <h5 class="mb-3 text-success">Đánh giá từng sản phẩm:</h5>

                                @foreach ($order->items as $index => $item)
                                    @php
                                        $image = $item->color && $item->color->image
                                            ? asset('storage/' . $item->color->image)
                                            : asset('storage/' . $item->product->image);
                                        $review = $item->review;
                                    @endphp

                                    <div class="card mb-4 p-4 border rounded shadow-sm">
                                        {{-- Thông tin sản phẩm --}}
                                        <h6 class="mb-3 text-primary">Sản phẩm:</h6>
                                        <div class="d-flex align-items-start mb-3 border-bottom pb-3">
                                            <img src="{{ $image }}" width="80" height="80" class="me-3 rounded" alt="{{ $item->product->name }}">
                                            <div>
                                                <strong>{{ $item->product->name }}</strong><br>
                                                @if ($item->color)
                                                    <small class="text-muted">Màu: {{ $item->color->color }}</small><br>
                                                @endif
                                                @if ($item->size)
                                                    <small class="text-muted">Size: {{ $item->size }}</small>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Nếu đã đánh giá rồi --}}
                                        @if ($review)
                                            <div>
                                                <h6 class="text-success">Đã đánh giá:</h6>
                                                <p><strong>⭐ {{ $review->rating }} sao</strong></p>
                                                <p>{{ $review->content }}</p>
                                                @if ($review->image)
                                                    <img src="{{ asset('storage/' . $review->image) }}" class="img-thumbnail mt-2" style="max-width: 150px;">
                                                @endif
                                            </div>
                                        @else
                                            {{-- Nếu chưa đánh giá --}}
                                            <h6 class="text-warning">Đánh giá của bạn:</h6>

                                            <input type="hidden" name="reviews[{{ $index }}][order_item_id]" value="{{ $item->id }}">

                                            <div class="form-group mb-3">
                                                <label><strong>Số sao:</strong></label>
                                                <select name="reviews[{{ $index }}][rating]" class="form-control" required>
                                                    <option value="">-- Chọn sao --</option>
                                                    @for ($i = 5; $i >= 1; $i--)
                                                        <option value="{{ $i }}">{{ $i }} ⭐</option>
                                                    @endfor
                                                </select>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label><strong>Bình luận:</strong></label>
                                                <textarea name="reviews[{{ $index }}][content]" rows="3" class="form-control" required></textarea>
                                            </div>

                                            <div class="form-group mb-2">
                                                <label><strong>Ảnh minh họa (nếu có):</strong></label>
                                                <input type="file" name="reviews[{{ $index }}][image]" class="form-control" accept="image/*">
                                            </div>
                                        @endif
                                    </div>
                                @endforeach

                                <div class="text-end mt-4">
                                    <a href="{{ route('order.index') }}" class="btn btn-secondary ms-2">
                                        <i class="fa fa-arrow-left"></i> Quay lại
                                    </a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
