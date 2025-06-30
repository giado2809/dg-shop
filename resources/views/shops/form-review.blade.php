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

                            <div class="mb-3">
                                <strong>Họ tên:</strong> {{ $order->name }}<br>
                                <strong>Email:</strong> {{ $order->email ?? 'Không có' }}<br>
                                <strong>Số điện thoại:</strong> {{ $order->phone }}<br>
                                <strong>Địa chỉ:</strong> {{ $order->address }}<br>
                                <strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
                            </div>

                            <form action="{{ route('review.store', $order->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                                @csrf

                                <h5 class="mb-3">Đánh giá từng sản phẩm:</h5>
                                @foreach ($order->items as $index => $item)
                                    @php
                                        $image = $item->color && $item->color->image
                                                ? asset('storage/' . $item->color->image)
                                                : asset('storage/' . $item->product->image);
                                        $review = $item->review;
                                    @endphp

                                    <div class="card mb-4 p-3 border">
                                        <div class="d-flex align-items-start">
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

                                        @if ($review)
                                            <div class="mt-3">
                                                <p><strong>Đã đánh giá:</strong></p>
                                                <p>⭐ {{ $review->rating }} sao</p>
                                                <p>{{ $review->content }}</p>
                                                @if ($review->image)
                                                    <img src="{{ asset('storage/' . $review->image) }}" alt="Review Image" class="img-thumbnail" style="max-width: 150px;">
                                                @endif
                                            </div>
                                        @else
                                            {{-- Hidden ID --}}
                                            <input type="hidden" name="reviews[{{ $index }}][order_item_id]" value="{{ $item->id }}">

                                            <div class="mt-3">
                                                <div class="form-group mb-2">
                                                    <label><strong>Đánh giá (sao):</strong></label>
                                                    <select name="reviews[{{ $index }}][rating]" class="form-control" required>
                                                        <option value="">-- Chọn sao --</option>
                                                        @for ($i = 5; $i >= 1; $i--)
                                                            <option value="{{ $i }}">{{ $i }} ⭐</option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                <div class="form-group mb-2">
                                                    <label><strong>Bình luận:</strong></label>
                                                    <textarea name="reviews[{{ $index }}][content]" rows="3" class="form-control" required></textarea>
                                                </div>

                                                <div class="form-group mb-2">
                                                    <label><strong>Ảnh minh họa:</strong></label>
                                                    <input type="file" name="reviews[{{ $index }}][image]" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach

                                <div class="text-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-paper-plane"></i> Gửi đánh giá
                                    </button>
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
