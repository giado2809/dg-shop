@extends('layouts.shops.layout')

@section('title', 'Detail')

@section('style')
    <style>
        .add-to-cart-btn, .buy-btn {
            border: none;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .add-to-cart-btn {
            background-color: #ff9d00;
            color: white;
        }

        .add-to-cart-btn:hover {
            background-color: #ffcb77;
            transform: translateY(-1px);
        }

        .buy-btn {
            background-color: #ff0000;
            color: white;
        }

        .buy-btn:hover {
            background-color: #ff7676;
            transform: translateY(-1px);
        }

        .color-option {
            display: inline-flex;
            align-items: center;
            border: 2px solid #ddd;
            padding: 4px 8px;
            margin: 4px;
            border-radius: 6px;
            cursor: pointer;
        }

        .color-option img {
            width: 30px;
            height: 30px;
            object-fit: cover;
            margin-right: 8px;
        }

        .color-option.active {
            border-color: #333;
        }

        .size-option {
            display: inline-block;
            border: 2px solid #ddd;
            border-radius: 4px;
            padding: 6px 12px;
            margin: 4px;
            cursor: pointer;
        }

        .size-option.active {
            border-color: #333;
            background-color: #f0f0f0;
        }

        .thumb-wrapper {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            margin-top: 10px;
        }

        .thumb-wrapper img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .thumb-wrapper img.active {
            border-color: #333;
        }

        .color-option.disabled,
        .size-option.disabled {
            pointer-events: none;
            opacity: 0.5;
        }
        .pro-qty-vertical {
            position: relative;
            display: inline-block;
            width: 60px;
        }

        .pro-qty-vertical input.qty-input {
            width: 100%;
            height: 36px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding-right: 25px; /* chừa chỗ cho 2 nút */
        }

        .pro-qty-vertical .qty-buttons {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .pro-qty-vertical .qtybtn {
            width: 25px;
            height: 50%;
            border: none;
            background: none;
            font-size: 14px;
            cursor: pointer;
            line-height: 18px;
            color: #333;
        }

    </style>
@endsection

@section('content')
    <section class="shop-details pt-5">
        <div class="container">
            <div class="row">
                <!-- Cột ảnh chi tiết -->
                <div class="col-lg-5">
                    <div class="product__details__pic__item mb-3">
                        <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" alt="" class="img-fluid w-100">
                    </div>
                    <div class="thumb-wrapper">
                        {{-- Ảnh chính --}}
                        <img src="{{ asset('storage/' . $product->image) }}" data-color-id="main" class="active" onclick="selectMainImage()">

                        {{-- Ảnh từng màu --}}
                        @foreach ($product->colors as $color)
                            <img src="{{ asset('storage/' . $color->image) }}" data-color-id="{{ $color->id }}" onclick="selectColor({{ $color->id }})">
                        @endforeach
                    </div>
                </div>

                <!-- Cột nội dung chi tiết -->
                <div class="col-lg-7">
                    <div class="product__details__text text-left pl-lg-4">
                        <h4>{{ $product->name }}</h4>
                        <div style="display: flex; gap: 8px; align-items: center; margin: 6px 0 12px 0; font-size: 14px; color: #888;">
                            <span>⭐ {{ number_format($product->avg_rating ?? 0, 1) }}</span>
                            <span>|</span>
                            <span>Đã bán: {{ number_format($totalSold) }}</span>
                        </div>

                        @if ($product->sale_price > 0)
                            <h3 style="display: flex; align-items: center; gap: 10px;">
                                <del style="color: #888; font-size: 20px; margin: 0;">{{ number_format($product->price, 0, ',', '.') }}đ</del>
                                <span style="color: #e53637; font-weight: 600; font-size: 24px; margin: 0; text-decoration: none !important;">
                                    {{ number_format($product->sale_price, 0, ',', '.') }}đ
                                </span>
                            </h3>
                        @else
                            <h3 class="text-danger" style="
                            color: #e53637; font-weight: 600; font-size: 24px;
                            margin: 0; text-decoration: none !important;">
                                {{ number_format($product->price, 0, ',', '.') }}đ
                            </h3>
                        @endif

                        <!-- Chọn màu -->
                        <div class="mb-3" style="margin-top: 16px;">
                            <strong>Màu:</strong>
                            <div id="colorList">
                                @foreach ($product->colors as $color)
                                    <div class="color-option" data-color-id="{{ $color->id }}" onclick="selectColor({{ $color->id }})">
                                        <img src="{{ asset('storage/' . $color->image) }}" alt="">
                                        <span>{{ $color->color }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Chọn size -->
                        <div class="mb-3">
                            <strong>Size:</strong>
                            <div id="sizeList"></div>
                        </div>

                        <!-- Số lượng còn lại -->
                        <div class="mb-2">
                            <span>Còn lại: <span id="stockLeft">0</span> sản phẩm</span>
                        </div>

                        <!-- Số lượng và nút -->
                        <div class="product__details__cart__option flex items-center space-x-2 mb-3">
                            <div class="quantity">
                                <div class="pro-qty-vertical">
                                    <input type="text" value="1" class="qty-input">
                                    <div class="qty-buttons">
                                        <button class="qtybtn inc"><i class="fa fa-angle-up"></i></button>
                                        <button class="qtybtn dec"><i class="fa fa-angle-down"></i></button>
                                    </div>
                                </div>
                            </div>
                            <button class="add-to-cart-btn" data-id="{{ $product->id }}">Add to Cart</button>
                            <button class="buy-btn" data-id="{{ $product->id }}">Buy now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mô tả và thông tin -->
    <div class="container">
        <section class="product-details-tab-section">
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-5" role="tab">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-6" role="tab">Customer Reviews ({{ $product->reviews->count() }})</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-7" role="tab">Additional Information</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-5" role="tabpanel">
                                <div class="product__details__tab__content">
                                    <div class="product__details__tab__content__item">
                                        <h5>Products Information</h5>
                                        <p>{!! $product->description !!}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tabs-6" role="tabpanel">
                                <div class="product__details__tab__content">
                                    <div class="product__details__tab__content__item">
                                        <h5>Customer Reviews</h5>

                                        @if ($product->reviews->isEmpty())
                                            <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                                        @else
                                            @foreach ($product->reviews as $review)
                                                <div class="border rounded p-3 mb-3 bg-light">
                                                    <div class="d-flex justify-content-between">
                                                        <strong>{{ $review->user->username }}</strong>
                                                        <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                                    </div>
                                                    <div class="text-warning my-1">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                                        @endfor
                                                    </div>
                                                    <p>{{ $review->content }}</p>

                                                    @if ($review->image)
                                                        <img src="{{ asset('storage/' . $review->image) }}" alt="Ảnh đánh giá" class="img-thumbnail mt-2" width="150">
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tabs-7" role="tabpanel">
                                <div class="product__details__tab__content">
                                    <div class="product__details__tab__content__item">
                                        <h5>Material Used</h5>
                                        <p>. . .</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="related spad">
            <h3 class="related-title">Related Product</h3>
            <div class="row">
                @foreach ($relatedProducts as $item)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product__item {{ $item->tag === 'hot-sales' ? 'sale' : '' }}">
                            <a href="{{ route('detail', $item->id) }}">
                                <div class="product__item__pic set-bg" data-setbg="{{ asset('storage/' . $item->image) }}">
                                    @if ($item->tag === 'new-arrivals')
                                        <span class="label">New</span>
                                    @elseif ($item->tag === 'hot-sales')
                                        <span class="label">Sale</span>
                                    @endif
                                </div>
                                <div class="product__item__text">
                                    <h6>{{ $item->name }}</h6>
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; min-height: 40px;">
                                        @if ($item->sale_price > 0)
                                            <h5 style="margin: 0;">
                                                <del style="color: #999; font-size: 14px;">{{ number_format($item->price, 0, ',', '.') }}đ</del>
                                                <span style="padding-left: 5px;">
                                                    {{ number_format($item->sale_price, 0, ',', '.') }}đ
                                                </span>
                                            </h5>
                                        @else
                                            <h5 style="margin: 0;">
                                                {{ number_format($item->price, 0, ',', '.') }}đ
                                            </h5>
                                        @endif

                                        <div style="text-align: right; display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                                            <div style="font-size: 13px; color: #666;">
                                                Đã bán: {{ number_format($item->total_sold ?? 0) }}
                                            </div>
                                            <div style="font-size: 13px; color: #666; margin-top: 5px;">
                                                ⭐ {{ number_format($item->avg_rating ?? 0, 1) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

@endsection

@section('script')
    <script>
        const productVariants = @json($productVariants);

        document.addEventListener("DOMContentLoaded", () => {
            // Ban đầu disable hết
            disableQuantityControls();

            // Ẩn các màu hết hàng
            Object.keys(productVariants).forEach(colorId => {
                const sizes = productVariants[colorId].sizes;
                const hasStock = sizes.some(s => s.quantity > 0);
                if (!hasStock) {
                    const colorDiv = document.querySelector(`.color-option[data-color-id="${colorId}"]`);
                    if (colorDiv) {
                        colorDiv.classList.add("disabled");
                        colorDiv.style.pointerEvents = "none";
                        colorDiv.style.opacity = "0.5";
                    }
                }
            });

            bindCartButtons(); // Gọi bind sau khi DOM ready
        });

        function disableQuantityControls() {
            document.querySelector('.qty-input').disabled = true;
            document.querySelectorAll('.qtybtn').forEach(btn => {
                btn.disabled = true;
                btn.style.opacity = "0.5";
                btn.style.pointerEvents = "none";
            });
            document.getElementById('stockLeft').parentElement.style.display = "none";
        }

        function enableQuantityControls() {
            document.querySelector('.qty-input').disabled = false;
            document.querySelectorAll('.qtybtn').forEach(btn => {
                btn.disabled = false;
                btn.style.opacity = "1";
                btn.style.pointerEvents = "auto";
            });
            document.getElementById('stockLeft').parentElement.style.display = "inline";
        }

        function selectColor(colorId) {
            const colorDiv = document.querySelector(`.color-option[data-color-id="${colorId}"]`);
            if (colorDiv && colorDiv.classList.contains("disabled")) return;

            document.querySelectorAll('.color-option').forEach(el => el.classList.remove('active'));
            colorDiv.classList.add('active');

            document.getElementById('mainImage').src = productVariants[colorId].image;
            document.querySelectorAll('.thumb-wrapper img').forEach(el => el.classList.remove('active'));
            document.querySelector(`.thumb-wrapper img[data-color-id="${colorId}"]`).classList.add('active');

            const sizeList = document.getElementById('sizeList');
            sizeList.innerHTML = '';

            let hasSizeInStock = false;

            productVariants[colorId].sizes.forEach(size => {
                const div = document.createElement('div');
                div.classList.add('size-option');
                div.textContent = size.size;

                if (size.quantity > 0) {
                    div.onclick = function () {
                        document.querySelectorAll('.size-option').forEach(el => el.classList.remove('active'));
                        this.classList.add('active');

                        const stockQty = size.quantity;
                        document.getElementById('stockLeft').textContent = stockQty;

                        // Chỉ enable khi chọn đúng size còn hàng
                        enableQuantityControls();

                        const qtyInput = document.querySelector('.qty-input');
                        if (qtyInput) {
                            qtyInput.max = stockQty;
                            if (parseInt(qtyInput.value) > stockQty) {
                                qtyInput.value = 1;
                            }
                        }
                    };
                    hasSizeInStock = true;
                } else {
                    div.classList.add('disabled');
                    div.style.pointerEvents = 'none';
                    div.style.opacity = '0.5';
                }

                sizeList.appendChild(div);
            });

            // Sau khi chọn màu → nhưng chưa chọn size → vẫn disable nha
            disableQuantityControls();
            document.getElementById('stockLeft').textContent = 0;
        }

        function selectMainImage() {
            document.querySelectorAll('.thumb-wrapper img').forEach(el => el.classList.remove('active'));
            document.querySelector('.thumb-wrapper img[data-color-id="main"]').classList.add('active');

            document.querySelectorAll('.color-option').forEach(el => el.classList.remove('active'));

            document.getElementById('mainImage').src = "{{ asset('storage/' . $product->image) }}";

            document.getElementById('sizeList').innerHTML = '';
            document.getElementById('stockLeft').textContent = 0;
        }
        
        function bindCartButtons() {
            const csrfToken = '{{ csrf_token() }}';

            document.querySelectorAll('.add-to-cart-btn, .buy-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    if (!isAuthenticated) {
                        window.location.href = '/login';
                        return;
                    }
                    const productId = this.getAttribute('data-id');
                    const colorElement = document.querySelector('.color-option.active');
                    const sizeElement = document.querySelector('.size-option.active');
                    const quantityInput = document.querySelector('.qty-input'); // ← dùng đúng class mới

                    if (!colorElement || !sizeElement) {
                        alert("Vui lòng chọn màu và size.");
                        return;
                    }

                    const colorId = colorElement.getAttribute('data-color-id');
                    const size = sizeElement.textContent.trim();
                    const quantity = parseInt(quantityInput.value);
                    const isBuy = this.classList.contains('buy-btn');

                    if (!quantity || quantity <= 0) {
                        alert("Số lượng không hợp lệ.");
                        return;
                    }

                    fetch("{{ route('cart.add') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            color_id: colorId,
                            size: size,
                            quantity: quantity,
                            mark_as_selected: isBuy ? true : false,
                            mode: isBuy ? 'buy' : 'add'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (isBuy) {
                            if (data.checked_id) {
                                localStorage.setItem('autoCheckedCartId', data.checked_id);
                            }
                            window.location.href = data.redirect || '/cart';
                        } else {
                            alert(data.message || 'Đã thêm vào giỏ hàng!');
                            if (data.cart_count !== undefined) {
                                const countSpan = document.getElementById('cart-count');
                                if (countSpan) {
                                    countSpan.textContent = data.cart_count;
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi thêm vào giỏ hàng!');
                    });
                });
            });
        }

        document.querySelectorAll('.pro-qty-vertical .qtybtn').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = btn.closest('.pro-qty-vertical').querySelector('input.qty-input');
                let val = parseInt(input.value) || 1;
                const max = parseInt(input.max) || Infinity;

                if (btn.classList.contains('inc')) {
                    if (val < max) {
                        val++;
                    } else {
                        alert(`Chỉ còn ${max} sản phẩm trong kho!`);
                    }
                } else {
                    val = val > 1 ? val - 1 : 1;
                }

                input.value = val;
            });
        });
    </script>
@endsection


