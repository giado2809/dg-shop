@extends('layouts.shops.layout')

@section('title', 'Chỉnh sửa thông tin cá nhân')

@section('content')
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form" style="max-width: 600px; margin: 0 auto;">
                <h4 class="mb-4">Thông tin cá nhân</h4>
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Họ tên --}}
                    <div class="checkout__input">
                        <p>Họ tên<span>*</span></p>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                    </div>

                    {{-- Email --}}
                    <div class="checkout__input">
                        <p>Email<span>*</span></p>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                    </div>

                    {{-- SĐT --}}
                    <div class="checkout__input">
                        <p>Số điện thoại</p>
                        <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                    </div>

                    {{-- Ảnh đại diện --}}
                    <div class="checkout__input">
                        <p>Ảnh đại diện</p>
                        @if (Auth::user()->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="avatar" width="80" style="border-radius: 8px;">
                            </div>
                            <div class="mb-2" style="font-size: 14px;">
                                <label style="display: flex; align-items: center; gap: 6px;">
                                    <input type="checkbox" name="delete_image" value="1" style="width: 16px; height: 16px;">
                                    <span>Xoá ảnh hiện tại</span>
                                </label>
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*" style="border: none; box-shadow: none; padding-left: 0;">
                    </div>

                    <button type="submit" class="site-btn mt-3 px-4">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </section>
@endsection
