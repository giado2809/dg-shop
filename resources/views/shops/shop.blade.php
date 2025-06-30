@extends('layouts.shops.layout')

@section('title', 'Shop')

@section('style')
    <style>
        #sort-by-price {
            display: block !important;
            border: none;
            font-weight: 600;
        }

        .shop__product__option__right {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px; /* Khoảng cách giữa p và select */
        }

        .shop__product__option__right p {
            margin: 0;
            font-weight: 500;
        }
    </style>
@endsection

@section('content')
    <!-- Shop Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="shop__sidebar">
                        <div class="shop__sidebar__search">
                            <form action="{{ route('shop') }}" method="GET">
                                <input type="text" placeholder="Search..." name="search" value="{{ request('search') }}">
                                <button type="submit"><span class="icon_search"></span></button>
                            </form>
                        </div>
                        <div class="shop__sidebar__accordion">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseOne">Categories</a>
                                    </div>
                                    <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__categories">
                                                <ul class="nice-scroll">
                                                    @foreach ($categories as $cat)
                                                        <li>
                                                            <a href="{{route('shop', ['category' => $cat->id] )}}">
                                                                {{$cat->name}} ({{$cat->products_count}})
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapsePrice">Khoảng Giá</a>
                                    </div>
                                    <div id="collapsePrice" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <form method="GET" action="{{ route('shop') }}">
                                                <div class="form-group d-flex align-items-center">
                                                    <input type="number" name="price_from" class="form-control mr-2" placeholder="₫ TỪ"
                                                        value="{{ request('price_from') }}">
                                                    <span> - </span>
                                                    <input type="number" name="price_to" class="form-control ml-2" placeholder="₫ ĐẾN"
                                                        value="{{ request('price_to') }}">
                                                </div>
                                                <button type="submit" class="btn btn-danger btn-block mt-2">ÁP DỤNG</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="shop__product__option">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__left">
                                    <p>Showing 1–12 of 126 results</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__right">
                                    <p>Sort by Price:</p>
                                    <select id="sort-by-price">
                                        <option value="{{ route('shop', array_merge(request()->query(), ['sort' => 'asc'])) }}"
                                            {{ request()->query('sort') == 'asc' ? 'selected' : '' }}>                             
                                            Low To High
                                        </option>
                                        <option value="{{ route('shop', array_merge(request()->query(), ['sort' => 'desc'])) }}"
                                            {{ request()->query('sort') == 'desc' ? 'selected' : '' }}>
                                            High To Low
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($products->count() > 0)
                        @if (isset($search))
                            <h3 class="text-2xl font-bold mb-4" style="color: red" id="search-results">Kết quả tìm kiếm cho: "{{ $search }}"</h3>
                        @elseif (isset($categoryName))
                            <h3 class="text-2xl font-bold mb-4" style="color: red" id="search-results">Sản phẩm thuộc danh mục: "{{ $categoryName }}"</h3>
                        @endif
                    <div class="row">      
                        @foreach ($products as $pro)
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item {{ $pro->tag === 'hot-sales' ? 'sale' : '' }}">
                                    <a href="{{route('detail', $pro->id)}}">
                                        <div class="product__item__pic set-bg" data-setbg="{{asset ('storage/' .$pro->image)}}">
                                            @if ($pro->tag === 'new-arrivals')
                                            <span class="label">New</span>
                                            @elseif ($pro->tag === 'hot-sales')
                                            <span class="label">Sale</span>
                                            @endif
                                        </div>
                                        <div class="product__item__text">
                                            <h6>{{ $pro->name }}</h6>
                                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                                {{-- Bên trái: Giá tiền --}}
                                                <div>
                                                    @if ($pro->sale_price > 0)
                                                        <h5 style="margin: 0;">
                                                            <del style="color: #999; font-size: 14px;">{{ number_format($pro->price, 0, ',', '.') }}đ</del>
                                                            <span>{{ number_format($pro->sale_price, 0, ',', '.') }}đ</span>
                                                        </h5>
                                                    @else
                                                        <h5 style="margin: 0;">{{ number_format($pro->price, 0, ',', '.') }}đ</h5>
                                                    @endif
                                                </div>

                                                {{-- Bên phải: Đã bán và sao --}}
                                                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 3px;">
                                                    <span style="font-size: 13px; color: #666; white-space: nowrap;">
                                                        Đã bán: {{ number_format($pro->total_sold ?? 0) }}
                                                    </span>
                                                    <span style="font-size: 13px; color: #666;">
                                                        ⭐ {{ number_format($pro->avg_rating ?? 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>                             
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h3 class="text-2xl font-bold mb-4" style="color: red" id="search-results">Không tìm thấy sản phẩm này"</h3>     
                    @endif
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="product__pagination">
                                <a class="active" href="#">1</a>
                                <a href="#">2</a>
                                <a href="#">3</a>
                                <span>...</span>
                                <a href="#">21</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section End -->
@endsection

@section('script')
    <script>
        // Xóa dropdown giả do niceSelect tạo ra nếu còn sót
        document.addEventListener('DOMContentLoaded', function () {
            const fakeSelect = document.querySelector('.nice-select');
            if (fakeSelect) fakeSelect.remove();
        });

        // Bắt sự kiện chọn dropdown thật
        document.getElementById('sort-by-price').addEventListener('change', function () {
            const url = this.value;
            if (url) {
                window.location.href = url;
            }
        });
    </script>
@endsection


    