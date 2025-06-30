@extends('layouts.shops.layout')

@section('title', 'Checkout')

@section('content')
    
    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <form action="{{ route('checkout.placeOrder') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-5">
                            <h6 class="coupon__code"><span class="icon_tag_alt"></span> Have a coupon? <a href="#">Click
                            here</a> to enter your code</h6>
                            <h6 class="checkout__title">Billing Details</h6>
                            <div class="checkout__input">
                                <p>Họ tên<span>*</span></p>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" required>
                            </div>
                            <div class="checkout__input">
                                <p>Email<span>*</span></p>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required>
                            </div>
                            <div class="checkout__input">
                                <p>Số điện thoại<span>*</span></p>
                                <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                            </div>
                            <div class="checkout__input">
                                <p>Địa chỉ<span>*</span></p>
                                <input type="text" name="address" value="{{ old('address', Auth::user()->address ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="checkout__order">
                                <h4 class="order__title">Your order</h4>

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
                                        @foreach ($cart as $item)
                                            <input type="hidden" name="selected_ids_string" value="{{ implode(',', $cart->pluck('id')->toArray()) }}">
                                            <input type="hidden" name="selected_cart_ids[]" value="{{ $item->id }}">
                                            <tr>
                                                <td width="80">
                                                    @php
                                                        $image = $item->color && $item->color->image
                                                        ? asset('storage/' . $item->color->image)
                                                        : asset('storage/' . $item->product->image);
                                                    @endphp
                                                    <img src="{{ $image }}" alt="{{ $item->product->name }}">
                                                </td>
                                                <td>
                                                    {{ $item->product->name }} <br>
                                                    @if ($item->color_id)
                                                        <small class="text-muted">Màu: {{ optional($item->color)->color ?? 'N/A' }}</small><br>
                                                    @endif
                                                    @if ($item->size)
                                                        <small class="text-muted">Size: {{ $item->size }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($item->product->price, 0, ',', '.') }}đ</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}đ</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <ul class="checkout__total__all">
                                    <li>Subtotal <span>{{ number_format($total, 0, ',', '.') }}đ</span></li>                
                                </ul>
                                {{-- Nhập mã giảm giá --}}
                                <div class="form-group mt-3">
                                    <label for="voucher_code">Mã giảm giá</label>
                                    <div class="input-group">
                                        <input type="text" name="voucher_code" id="voucher_code" class="form-control" placeholder="Nhập mã giảm giá">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="applyVoucher()">Áp dụng</button>
                                        </div>
                                    </div>
                                    <div id="voucher-error" class="text-danger small mt-1"></div>
                                </div>
                                <button type="submit" class="site-btn">PLACE ORDER</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->
    
@endsection

@section('script')
    <script>
        function applyVoucher() {
            const code = document.getElementById('voucher_code').value;
            const totalText = document.querySelector('.checkout__total__all span').innerText;
            const total = parseInt(totalText.replace(/\D/g, ''));

            fetch("{{ route('voucher.check') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code, total })
            })
            .then(res => res.json())
            .then(data => {
                const errorBox = document.getElementById('voucher-error');
                errorBox.innerText = '';

                // Xoá kết quả cũ nếu có
                document.getElementById('voucher-result')?.remove();
                document.getElementById('final-total')?.remove();

                if (data.error) {
                    errorBox.innerText = data.error;
                    return;
                }

                // Thêm kết quả giảm giá
                let info = `
                    <li id="voucher-result">Giảm giá (${data.code} - ${data.percent}%) <span class="text-danger">- ${data.discount.toLocaleString()}đ</span></li>
                    <li id="final-total"><strong>Thành tiền</strong> <span class="text-primary">${data.final_total.toLocaleString()}đ</span></li>
                `;
                document.querySelector('.checkout__total__all').insertAdjacentHTML('beforeend', info);
            })
            .catch(err => {
                document.getElementById('voucher-error').innerText = 'Đã có lỗi xảy ra!';
            });
        }
    </script>
@endsection

