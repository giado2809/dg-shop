@extends('layouts.auths.layout-form-login')

@section('title', 'login')

@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="{{asset('admins/images/team.jpg')}}" alt="IMG">
                </div>
                <!--=====TIÊU ĐỀ======-->
                <form class="login100-form validate-form" action="{{ route('changePassword.submit') }}" method="POST">
                    
                    @csrf
                    <span class="login100-form-title"><b>ĐỔI MẬT KHẨU</b></span>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="wrap-input100 validate-input">
                        <input autocomplete="off" class="input100" type="password" placeholder="Mật khẩu hiện tại"
                        name="current_password" id="password-field" required>
                        <span toggle="#password-field" class="bx fa-fw bx-hide field-icon click-eye"></span>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class='bx bx-key'></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <input autocomplete="off" class="input100" type="password" placeholder="Mật khẩu mới"
                        name="new_password" id="confirm-password-field" required>
                        <span toggle="#password-field" class="bx fa-fw bx-hide field-icon click-eye"></span>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class='bx bx-key'></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <input autocomplete="off" class="input100" type="password" placeholder="Xác nhận mật khẩu mới"
                        name="new_password_confirmation" id="confirm-password-field" required>
                        <span toggle="#password-field" class="bx fa-fw bx-hide field-icon click-eye"></span>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class='bx bx-key'></i>
                        </span>
                    </div>
                    <div class="d-flex flex-column gap-2 pt-3">
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                            Xác nhận
                        </button>

                        <a href="{{ route('index') }}" class="btn btn-outline-secondary w-100 fw-bold py-2 text-center mt-2">
                            Quay lại
                        </a>
                    </div>
          
                    <!--=====FOOTER======-->
                    <div class="text-center p-t-70 txt2">
                        Template phần mềm quản lý bán hàng <i class="far fa-copyright" aria-hidden="true"></i>
                        <script type="text/javascript">document.write(new Date().getFullYear());</script> <a
                            class="txt2" href="https://www.facebook.com/truongvo.vd1503/"> Code bởi Trường </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!--Javascript-->
    <script src="{{ asset('admins/js/js/main.js') }}"></script>
    <script src="https://unpkg.com/boxicons@latest/dist/boxicons.js"></script>
    <script src="{{ asset('admins/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('admins/vendor/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('admins/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admins/vendor/select2/select2.min.js') }}"></script>
    <script type="text/javascript">
        //show - hide mật khẩu
        function myFunction() {
            var x = document.getElementById("myInput");
            if (x.type === "password") {
                x.type = "text"
            } else {
                x.type = "password";
            }
        }
        $(".click-eye").click(function () {
            $(this).toggleClass("bx-show bx-hide");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>
@endsection


