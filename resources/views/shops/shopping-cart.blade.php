@extends('layouts.shops.layout')

@section('title', 'Cart')

@section('content')
    <!-- Shopping Cart Section Begin -->
        <section class="shopping-cart spad">
            <div class="container">
                <div class="row">

                    <div class="col-lg-9"> <!-- thay vì col-lg-8 -->
                        <div class="shopping__cart__table table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="text-center align-middle">
                                    <tr>
                                        <th style="width: 50px;"><input type="checkbox" id="select-all" class="form-check-input mx-auto d-block"></th>
                                        <th>Product</th>
                                        <th style="width: 100px;">Price</th>
                                        <th style="width: 150px;">Quantity</th>
                                        <th style="width: 100px;">Total</th>
                                        <th style="width: 80px;">Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($cartItems->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">🛒 Giỏ hàng của bạn đang trống</td>
                                        </tr>
                                    @else
                                        @foreach ($cartItems as $item)
                                            @php
                                                $image = $item->color && $item->color->image
                                                    ? asset('storage/' . $item->color->image)
                                                    : asset('storage/' . $item->product->image);
                                            @endphp
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <input type="checkbox"
                                                        class="form-check-input item-checkbox mx-auto d-block"
                                                        value="{{ $item->id }}"
                                                        data-price="{{ $item->product->price }}">
                                                </td>
                                                <td class="align-middle">
                                                    <div class="d-flex align-items-center">
                                                        <div style="width: 80px;">
                                                            <img src="{{ $image }}" alt="{{ $item->product->name }}" class="img-fluid rounded">
                                                        </div>
                                                        <div class="ml-3">
                                                            <strong>{{ $item->product->name }}</strong><br>
                                                            @if ($item->color_id)
                                                                <small>Màu: {{ optional($item->color)->color ?? 'N/A' }}</small><br>
                                                            @endif
                                                            @if ($item->size)
                                                                <small>Size: {{ $item->size }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">{{ number_format($item->product->price, 0, ',', '.') }}đ</td>
                                                <td class="text-center align-middle">
                                                    <div class="d-inline-flex align-items-center">
                                                        <button type="button" class="btn-qty btn btn-outline-secondary btn-sm px-2" data-action="decrease" data-id="{{ $item->id }}">-</button>
                                                        <span class="qty-value mx-2">{{ $item->quantity }}</span>
                                                        <button type="button" class="btn-qty btn btn-outline-secondary btn-sm px-2"
                                                            data-action="increase" data-id="{{ $item->id }}"
                                                            {{ $item->quantity >= ($item->variant_quantity ?? 9999) ? 'disabled' : '' }}>
                                                            +
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle cart__price cart__price-total" data-id="{{ $item->id }}">
                                                    {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}đ
                                                </td>
                                                <td class="text-center align-middle">
                                                    <a href="{{ route('checkout.buyOne', $item->id) }}"
                                                    class="btn btn-success btn-sm" title="Mua sản phẩm này">
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <a href="{{ route('shop') }}" class="btn btn-outline-primary w-100"><i class="fa fa-shopping-bag"></i> Continue Shopping</a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('cart.edit') }}" class="btn btn-outline-secondary w-100"><i class="fa fa-pencil"></i> Sửa</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3"> <!-- từ 4 chuyển thành 3 -->
                        <div class="cart__total border p-3">
                            <h6>Selected</h6>
                            <ul class="list-unstyled mb-3">
                                <li>Selected Product: <strong id="selected-count">0</strong></li>
                                <li>Selected Price: <strong id="selected-price">0đ</strong></li>
                            </ul>
                            {{-- Hiển thị voucher --}}
                            <h6 class="mt-4">🎁 Mã giảm giá đang có</h6>
                            <ul class="list-unstyled">
                                @forelse ($vouchers as $voucher)
                                    <li class="mb-2 d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $voucher->code }}</strong>
                                            <small class="text-muted">-{{ $voucher->discount_percent }}% (>{{ number_format($voucher->min_total, 0, ',', '.') }}đ)</small>
                                        </div>
                                        <span>{{ $voucher->quantity }} còn</span>
                                    </li>
                                @empty
                                    <li>Không có mã nào.</li>
                                @endforelse
                            </ul>

                            <a href="#" onclick="submitSelectedCheckout()" class="btn btn-primary w-100">Proceed to checkout</a>
                            <form id="checkout-selected-form" method="POST" action="{{ route('checkout') }}">
                                @csrf
                                <input type="hidden" name="selected_ids" id="checkout-selected-ids">
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    <!-- Shopping Cart Section End -->

@endsection

@section('script')
    <script>
        // Cập nhật tổng selected sản phẩm & giá
        function updateSelectedSummary() {
            const checkboxes = document.querySelectorAll('.item-checkbox:checked');
            let totalCount = 0;
            let totalPrice = 0;

            checkboxes.forEach(cb => {
                const price = parseFloat(cb.dataset.price);
                const quantity = parseInt(cb.closest('tr').querySelector('.qty-value').textContent);
                totalCount += quantity;
                totalPrice += price * quantity;
            });

            document.getElementById('selected-count').innerText = totalCount;
            document.getElementById('selected-price').innerText = formatVND(totalPrice);
        }

        // tự động tick checkbox nếu có id từ Buy Now
        document.addEventListener('DOMContentLoaded', () => {
            const checkedId = localStorage.getItem('autoCheckedCartId');
            if (checkedId) {
                const checkbox = document.querySelector(`.item-checkbox[value="${checkedId}"]`);
                if (checkbox) checkbox.checked = true;
                localStorage.removeItem('autoCheckedCartId');
            }

            updateSelectedSummary(); // cập nhật luôn khi load
        });

        // Tick chọn tất cả
        document.getElementById('select-all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelectedSummary();
        });

        // Khi tick chọn từng checkbox
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSelectedSummary);
        });

        // Nút tăng/giảm số lượng
        document.querySelectorAll('.btn-qty').forEach(btn => {
            btn.addEventListener('click', () => {
                const cartId = btn.dataset.id;
                const action = btn.dataset.action;
                const spanQty = btn.closest('td').querySelector('.qty-value');
                const totalCell = document.querySelector(`.cart__price-total[data-id="${cartId}"]`);

                fetch(`/cart/${action}/${cartId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (data.deleted) {
                            window.location.reload();
                        } else {
                            spanQty.textContent = data.quantity;
                            totalCell.textContent = formatVND(data.total);
                            updateSelectedSummary(); // cập nhật nếu đang chọn
                        }
                    } else {
                        alert(data.message || 'Có lỗi xảy ra');
                    }
                });
            });
        });

        function formatVND(number) {
            return number.toLocaleString('vi-VN') + 'đ';
        }


        // Mua hàng những sản phẩm đã chọn
        function submitSelectedCheckout() {
            const checked = document.querySelectorAll('.item-checkbox:checked');
            const selectedIds = Array.from(checked).map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Bạn chưa chọn sản phẩm nào để mua!');
                return;
            }

            document.getElementById('checkout-selected-ids').value = selectedIds.join(',');
            document.getElementById('checkout-selected-form').submit();
        }

        // Gắn submit vào global nếu cần dùng onclick
        window.submitSelectedCheckout = submitSelectedCheckout;
    </script>
@endsection


