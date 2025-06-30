@extends('layouts.auths.layout-form-login')

@section('title', 'forgot')

@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="{{asset('admins/images/fg-img.png')}}" alt="IMG">
                </div>
                
                <form method="POST" action="{{ route('forgot.send') }}" class="login100-form validate-form">
                    @csrf

                    <span class="login100-form-title">
                        <b>KHÔI PHỤC MẬT KHẨU</b>
                    </span>

                    {{-- Hiển thị thông báo thành công --}}
                    @if(session('success'))
                        <div style="color: green; text-align:center; margin-bottom: 10px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Hiển thị lỗi --}}
                    @error('email')
                        <div style="color: red; text-align:center; margin-bottom: 10px;">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="email" name="email" placeholder="Email của bạn" required value="{{ old('email') }}"/>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class='bx bx-mail-send'></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button type="submit" class="btn btn-warning w-100 fw-bold">
                        <i class='bx bx-mail-send'></i>
                        Gửi link đặt lại</button>
                    </div>

                    <div class="text-center p-t-12">
                        <a class="btn btn-outline-secondary w-100 fw-bold" href="{{ route('login') }}">
                            Trở về đăng nhập
                        </a>
                    </div>

                    <div class="text-center p-t-70 txt2">
                        Template phần mềm quản lý bán hàng <i class="far fa-copyright" aria-hidden="true"></i>
                        <script type="text/javascript">document.write(new Date().getFullYear());</script>
                        <a class="txt2" href="https://www.facebook.com/truongvo.vd1503/"> Code bởi Trường </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!--===============================================================================================-->
    <script src="{{ asset('admins/js/main.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('admins/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('admins/vendor/bootstrap/js/popper.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('admins/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('admins/vendor/select2/select2.min.js') }}"></script>
    <!--===============================================================================================-->
@endsection



