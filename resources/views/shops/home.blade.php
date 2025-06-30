@extends('layouts.shops.layout')

@section('title', 'Home')

@section('style')
    <style>
        .product__item__pic {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product__item:hover .product__item__pic {
            transform: scale(1.05); /* ảnh nhích lên nhẹ */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* đổ bóng mềm */
        }

        /* Ẩn hoàn toàn nút add to cart nếu còn class cũ */
        .product__item__text .add-to-cart-btn {
            display: none !important;
        }
    </style>
    <style>
        .category-item:hover {
            background: #fff0f0;
            transform: translateY(-2px);
            transition: 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section Begin -->
            <section class="hero" >
                <div class="hero__slider owl-carousel">
                    <div class="hero__items set-bg" data-setbg="{{asset('shops/img/hero/hero-1.jpg')}}">
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-5 col-lg-7 col-md-8">
                                    <div class="hero__text">
                                        <h6>Summer Collection</h6>
                                        <h2>Fall - Winter Collections 2030</h2>
                                        <p>A specialist label creating luxury essentials. Ethically crafted with an unwavering
                                        commitment to exceptional quality.</p>
                                        <a href="{{route('shop')}}" class="primary-btn">Shop now <span class="arrow_right"></span></a>
                                        <div class="hero__social">
                                            <a href="#"><i class="fa fa-facebook"></i></a>
                                            <a href="#"><i class="fa fa-twitter"></i></a>
                                            <a href="#"><i class="fa fa-pinterest"></i></a>
                                            <a href="#"><i class="fa fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hero__items set-bg" data-setbg="{{asset('shops/img/hero/hero-2.jpg')}}">
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-5 col-lg-7 col-md-8">
                                    <div class="hero__text">
                                        <h6>Summer Collection</h6>
                                        <h2>Fall - Winter Collections 2030</h2>
                                        <p>A specialist label creating luxury essentials. Ethically crafted with an unwavering
                                        commitment to exceptional quality.</p>
                                        <a href="{{route('shop')}}" class="primary-btn">Shop now <span class="arrow_right"></span></a>
                                        <div class="hero__social">
                                            <a href="#"><i class="fa fa-facebook"></i></a>
                                            <a href="#"><i class="fa fa-twitter"></i></a>
                                            <a href="#"><i class="fa fa-pinterest"></i></a>
                                            <a href="#"><i class="fa fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    <!-- Hero Section End -->

    <!-- Banner Section Begin -->
        <section class="banner spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 offset-lg-4">
                        <div class="banner__item">
                            <div class="banner__item__pic">
                                <img src="{{asset('shops/img/banner/banner-1.jpg')}}" alt="">
                            </div>
                            <div class="banner__item__text">
                                <h2>Clothing Collections 2030</h2>
                                <a href="{{route('shop')}}">Shop now</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="banner__item banner__item--middle">
                            <div class="banner__item__pic">
                                <img src="{{asset('shops/img/banner/banner-2.jpg')}}" alt="">
                            </div>
                            <div class="banner__item__text">
                                <h2>Accessories</h2>
                                <a href="{{route('shop')}}">Shop now</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="banner__item banner__item--last">
                            <div class="banner__item__pic">
                                <img src="{{asset('shops/img/banner/banner-3.jpg')}}" alt="">
                            </div>
                            <div class="banner__item__text">
                                <h2>Shoes Spring 2030</h2>
                                <a href="{{route('shop')}}">Shop now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <!-- Banner Section End -->

    <!-- Category Section Begin -->
        <section class="category spad" style="padding-top: 50px; padding-bottom: 50px;">
            <div class="container">
                <div class="section-title">
                    <h2>Danh mục</h2>
                </div>
                <div class="row">
                    @foreach ($categories as $cat)
                        <div class="col-lg-2 col-md-3 col-sm-4 col-6 text-center mb-4">
                            <a href="{{ route('shop', ['category' => $cat->id]) }}" style="text-decoration: none; color: #000;">
                                <div class="category-item" style="background: #f7f7f7; border-radius: 10px; padding: 15px;">
                                    <img src="{{ $cat->image ? asset('storage/' . $cat->image) : asset('shops/img/default-category.png') }}"
                                    alt="{{ $cat->name }}"
                                    style="width: 60px; height: 60px; object-fit: contain; margin-bottom: 10px;">
                                    <div style="font-weight: 500; font-size: 14px;">{{ $cat->name }}</div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    <!-- Category Section End -->

    <!-- Product Section Begin -->
        <section class="product spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="filter__controls">
                            <li class="active" data-filter="*">All Products</li>
                            <li data-filter=".new-arrivals">New Arrivals</li>
                            <li data-filter=".hot-sales">Hot Sales</li>
                        </ul>
                    </div>
                </div>
                <div class="row product__filter">
                    @foreach ($products as $pro)
                    @php
                        $tagClass = $pro->tag ?? '';
                    @endphp
                        <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mix {{$tagClass}}">
                            <div class="product__item {{ $pro->tag === 'hot-sales' ? 'sale' : '' }}">
                                <a href="{{route('detail', $pro->id)}}">
                                    <div class="product__item__pic set-bg" data-setbg="{{ asset('storage/' . $pro->image) }}">
                                        @if ($pro->tag === 'hot-sales')
                                            <span class="label">Sale</span>
                                        @elseif ($pro->tag === 'new-arrivals')
                                            <span class="label">New</span>
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
                </div>
            </div>
        </section>
    <!-- Product Section End -->

    <!-- Categories Section Begin -->
        <section class="categories spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="categories__text">
                            <h2>Clothings Hot <br /> <span>Shoe Collection</span> <br /> Accessories</h2>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="categories__hot__deal">
                            <img src="{{asset('shops/img/product-sale.png')}}" alt="">

                            <div class="hot__deal__sticker">
                                <span>Sale Of</span>
                                <h5>$29.99</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 offset-lg-1">
                        <div class="categories__deal__countdown">
                            <span>Deal Of The Week</span>
                            <h2>Multi-pocket Chest Bag Black</h2>
                            <div class="categories__deal__countdown__timer" id="countdown">
                                <div class="cd-item">
                                    <span>3</span>
                                    <p>Days</p>
                                </div>
                                <div class="cd-item">
                                    <span>1</span>
                                    <p>Hours</p>
                                </div>
                                <div class="cd-item">
                                    <span>50</span>
                                    <p>Minutes</p>
                                </div>
                                <div class="cd-item">
                                    <span>18</span>
                                    <p>Seconds</p>
                                </div>
                            </div>
                            <a href="{{route('shop')}}" class="primary-btn">Shop now</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <!-- Categories Section End -->

    <!-- Instagram Section Begin -->
        <section class="instagram spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="instagram__pic">
                            <div class="instagram__pic__item set-bg" data-setbg="{{asset('shops/img/instagram/instagram-1.jpg')}}"></div>
                            <div class="instagram__pic__item set-bg" data-setbg="{{asset('shops/img/instagram/instagram-2.jpg')}}"></div>
                            <div class="instagram__pic__item set-bg" data-setbg="{{asset('shops/img/instagram/instagram-3.jpg')}}"></div>
                            <div class="instagram__pic__item set-bg" data-setbg="{{asset('shops/img/instagram/instagram-4.jpg')}}"></div>
                            <div class="instagram__pic__item set-bg" data-setbg="{{asset('shops/img/instagram/instagram-5.jpg')}}"></div>
                            <div class="instagram__pic__item set-bg" data-setbg="{{asset('shops/img/instagram/instagram-6.jpg')}}"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="instagram__text">
                            <h2>Instagram</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua.</p>
                            <h3>#Male_Fashion</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <!-- Instagram Section End -->

    <!-- Latest Blog Section Begin -->
        <section class="latest spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <span>Latest News</span>
                            <h2>Fashion New Trends</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="blog__item">
                            <div class="blog__item__pic set-bg" data-setbg="{{asset('shops/img/blog/blog-1.jpg')}}"></div>
                            <div class="blog__item__text">
                                <span><img src="{{asset('shops/img/icon/calendar.png')}}" alt=""> 16 February 2020</span>
                                <h5>What Curling Irons Are The Best Ones</h5>
                                <a href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="blog__item">
                            <div class="blog__item__pic set-bg" data-setbg="{{asset('shops/img/blog/blog-2.jpg')}}"></div>
                            <div class="blog__item__text">
                                <span><img src="{{asset('shops/img/icon/calendar.png')}}" alt=""> 21 February 2020</span>
                                <h5>Eternity Bands Do Last Forever</h5>
                                <a href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="blog__item">
                            <div class="blog__item__pic set-bg" data-setbg="{{asset('shops/img/blog/blog-3.jpg')}}"></div>
                            <div class="blog__item__text">
                                <span><img src="{{asset('shops/img/icon/calendar.png')}}" alt=""> 28 February 2020</span>
                                <h5>The Health Benefits Of Sunglasses</h5>
                                <a href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <!-- Latest Blog Section End -->
@endsection


