<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title')</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('shops/css/bootstrap.min.css') }}" type="text/css"> 
    <link rel="stylesheet" href="{{ asset('shops/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('shops/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('shops/css/magnific-popup.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('shops/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('shops/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('shops/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('shops/css/style.css') }}" type="text/css">

    @yield('style')
    <style>
        ul.dropdown li {
            transition: background-color 0.3s, color 0.3s;
        }

        ul.dropdown li:hover {
            background-color: #e53637;  /* Màu nền khi hover (đỏ đẹp) */
            color: #fff;                /* Màu chữ trắng */
        }

        ul.dropdown li:hover a {
            color: #fff !important;     /* Chữ trong thẻ <a> cũng trắng */
        }
    </style>
    <style>
        .dropdown-user {
            display: inline-block;
            position: relative;
        }

        .dropdown-user:hover .dropdown-menu-user {
            display: block;
        }

        .dropdown-menu-user {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            z-index: 99;
            background: white;
            border: 1px solid #ddd;
            padding: 10px 0;
            list-style: none;
            min-width: 160px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .dropdown-menu-user li {
            padding: 8px 20px;
            white-space: nowrap;
        }

        .dropdown-menu-user li a,
        .dropdown-menu-user li button {
            color: red;
            text-decoration: none;
            width: 100%;
            display: block;
            text-align: left;
            background: none;
            border: none;
            padding: 0;
            font: inherit;
            cursor: pointer;
        }

        .dropdown-menu-user li a:hover,
        .dropdown-menu-user li button:hover {
            background-color: #f2f2f2;
        }
    </style>

</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__option">
            <div class="offcanvas__links">
                <a href="#">Sign in</a>
                <a href="#">FAQs</a>
            </div>
            <div class="offcanvas__top__hover">
                <span>Usd <i class="arrow_carrot-down"></i></span>
                <ul>
                    <li>USD</li>
                    <li>EUR</li>
                    <li>USD</li>
                </ul>
            </div>
        </div>
        <div class="offcanvas__nav__option">
            <a href="#" class="search-switch"><img src="{{ asset('shops/img/icon/search.png') }}" alt=""></a>
            <a href="#"><img src="{{ asset('shops/img/icon/heart.png') }}" alt=""></a>
            <a href="{{ route('cart.index') }}" id="real-cart-canvas">
                <img src="{{ asset('shops/img/icon/cart.png') }}" alt="">
            </a> 
            <div class="price">$0.00</div>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__text">
            <p>Free shipping, 30-day return or refund guarantee.</p>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <header class="header sticky-top bg-white shadow-sm z-50">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-7">
                        <div class="header__top__left">
                            <p>Free shipping, 30-day return or refund guarantee.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-5">
                        <div class="header__top__right">
                            <div class="header__top__links">
                            </div>
                            <div class="header__top__hover" style="position: relative;">
                                @if (Auth::check())
                                    <div class="dropdown-user" style="position: relative;">
                                        <div style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                            <img src="{{ asset(Auth::user()->image ? 'storage/' . Auth::user()->image : 'shops/img/default-avatar.png') }}"
                                            alt="Avatar" style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%;">
                                            <span style="color: white; font-weight: bolder;">
                                                {{ Auth::user()->name }} <i class="arrow_carrot-down"></i>
                                            </span>
                                        </div>                                   
                                        <ul class="dropdown-menu-user">
                                            <li>
                                                <form action="{{ route('logout') }}" method="POST">
                                                    @csrf
                                                    <button type="submit">Đăng xuất</button>
                                                </form>
                                            </li>
                                            <li><a href="{{route('changePassword')}}">Đổi Pass</a></li>
                                            <li><a href="{{route('user.profile.edit')}}">Sửa thông tin</a></li>
                                            <li><a href="{{route('cart.index')}}">Giỏ hàng</a></li>
                                            <li><a href="{{route('order.index')}}">Đơn hàng</a></li>
                                            @if (Auth::user()->role === 'admin')
                                                <li><a href="{{route('admin.dashboard')}}">Trang Admin</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                @else
                                    <a href="{{ route('login') }}">Sign in</a>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container ">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="header__logo">
                        <a href="{{ route('index') }}"><img src="{{ asset('shops/img/logo.png') }}" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li class="{{ request()->routeIs('index') ? 'active' : '' }}">
                                <a href="{{ route('index') }}">Home</a>
                            </li>
                            <li class="{{ request()->routeIs('shop') ? 'active' : '' }}">
                                <a href="{{ route('shop') }}">Shop</a>
                                <ul class="dropdown">
                                    <li><a href="{{route('shop')}}" style="text-align: center; font-weight:bolder">Category</a></li>
                                    @foreach ($categories as $cat)
                                        <li><a href="{{route('shop', ['category' => $cat->id])}}">{{$cat->name}}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                            {{-- <li><a href="#">Pages</a>
                                <ul class="dropdown">
                                    <li><a href="./about.html">About Us</a></li>
                                    <li><a href="./shop-details.html">Shop Details</a></li>
                                    <li><a href="./shopping-cart.html">Shopping Cart</a></li>
                                    <li><a href="./checkout.html">Check Out</a></li>
                                    <li><a href="./blog-details.html">Blog Details</a></li>
                                </ul>
                            </li> --}}
                            <li class="{{ request()->routeIs('about') ? 'active' : '' }}">
                                <a href="{{ route('about') }}">About</a>
                            </li>
                            <li class="{{ request()->routeIs('blog') ? 'active' : '' }}">
                                <a href="{{ route('blog') }}">Blog</a>
                            </li>
                            <li class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                                <a href="{{ route('contact') }}">Contacts</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="header__nav__option">
                        <a href="#" class="search-switch"><img src="{{ asset('shops/img/icon/search.png') }}" alt=""></a>
                        <a href="#"><img src="{{ asset('shops/img/icon/heart.png') }}" alt=""></a>
                        <a href="{{ route('cart.index') }}" id="real-cart" style="position: relative; display: inline-block;">
                            <img src="{{ asset('shops/img/icon/cart.png') }}" alt="">
                            <span id="cart-count" style="
                                position: absolute;
                                top: -10px;
                                right: -12px;
                                background: #e53637;
                                color: white;
                                font-size: 11px;
                                border-radius: 999px;
                                min-width: 18px;
                                height: 18px;
                                line-height: 18px;
                                text-align: center;
                                padding: 0 5px;
                                font-weight: bold;
                                box-shadow: 0 1px 3px rgba(0,0,0,0.2);
                            ">
                                {{ $cartCount ?? 0 }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
    </header>
    <!-- Header Section End -->

    <main>
        @yield('content')
    </main>

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__logo">
                            <a href="#"><img src="{{ asset('shops/img/footer-logo.png') }}" alt=""></a>
                        </div>
                        <p>The customer is at the heart of our unique business model, which includes design.</p>
                        <a href="#"><img src="{{ asset('shops/img/payment.png') }}" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Shopping</h6>
                        <ul>
                            <li><a href="#">Clothing Store</a></li>
                            <li><a href="#">Trending Shoes</a></li>
                            <li><a href="#">Accessories</a></li>
                            <li><a href="#">Sale</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Shopping</h6>
                        <ul>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">Payment Methods</a></li>
                            <li><a href="#">Delivary</a></li>
                            <li><a href="#">Return & Exchanges</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h6>NewLetter</h6>
                        <div class="footer__newslatter">
                            <p>Be the first to know about new arrivals, look books, sales & promos!</p>
                            <form action="#">
                                <input type="text" placeholder="Your email">
                                <button type="submit"><span class="icon_mail_alt"></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="footer__copyright__text">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        <p>Copyright ©
                            <script>
                                document.write(new Date().getFullYear());
                            </script>2020
                            All rights reserved | This template is made with <i class="fa fa-heart-o"
                            aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                        </p>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Search Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form action="{{ route('shop') }}" method="GET" class="search-model-form">
                <input type="text" name="search" id="search-input" placeholder="Search here....." value="{{ request('search') }}">
            </form>
        </div>
    </div>
    <!-- Search End -->

    <!-- Js Plugins -->
    <script src="{{ asset('shops/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('shops/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('shops/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('shops/js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('shops/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('shops/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('shops/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('shops/js/mixitup.min.js') }}"></script>
    <script src="{{ asset('shops/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('shops/js/main.js') }}"></script>
    @yield('script')

    <script>
        window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        document.addEventListener("DOMContentLoaded", function () {
            const cartIcon = document.getElementById("real-cart");
            if (cartIcon) {
                cartIcon.addEventListener("click", function (e) {
                    e.preventDefault();
                    if (!window.isAuthenticated) {
                        window.location.href = "/login";
                    } else {
                        window.location.href = "/cart"; // hoặc route('cart.index') nếu render bằng Blade
                    }
                });
            }
        });
    </script>
</body>

</html>