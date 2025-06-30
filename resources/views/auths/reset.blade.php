@extends('layouts.auths.layout-form-login')

@section('title', 'reset password')

@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="{{ asset('admins/images/fg-img.png') }}" alt="IMG">
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="login100-form validate-form">
                    @csrf

                    {{-- Token ẩn --}}
                    <input type="hidden" name="token" value="{{ $token }}">

                    <span class="login100-form-title">
                        <b>ĐẶT LẠI MẬT KHẨU</b>
                    </span>

                    {{-- Hiển thị lỗi --}}
                    @error('email')
                        <div style="color: red; text-align:center; margin-bottom: 10px;">
                            {{ $message }}
                        </div>
                    @enderror
                    @error('password')
                        <div style="color: red; text-align:center; margin-bottom: 10px;">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="email" name="email" placeholder="Email của bạn" required />
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class='bx bx-envelope'></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="password" name="password" placeholder="Mật khẩu mới" required />
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class='bx bx-lock'></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu" required />
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class='bx bx-lock'></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button type="submit">Đặt lại mật khẩu</button>
                    </div>

                    <div class="text-center p-t-12">
                        <a class="txt2" href="{{ route('login') }}">
                            Trở về đăng nhập
                        </a>
                    </div>

                    <div class="text-center p-t-70 txt2">
                        Template phần mềm quản lý bán hàng <i class="far fa-copyright"></i>
                        <script>document.write(new Date().getFullYear());</script>
                        <a class="txt2" href="https://www.facebook.com/truongvo.vd1503/"> Code bởi Trường </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('admins/js/main.js') }}"></script>
    <script src="{{ asset('admins/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('admins/vendor/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('admins/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admins/vendor/select2/select2.min.js') }}"></script>
@endsection
