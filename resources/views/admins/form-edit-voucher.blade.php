@extends('layouts.admins.layout-admin')

@section('title', 'Chỉnh sửa mã giảm giá')

@section('content')
<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><a href="{{ route('admin.voucher.index') }}"><b>Danh sách mã</b></a></li>
            <li class="breadcrumb-item active">Chỉnh sửa mã giảm giá</li>
        </ul>
        <div id="clock"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Chỉnh sửa mã giảm giá</h3>
                <div class="tile-body">
                    <div class="d-flex justify-content-center">
                        <div class="col-md-6">
                            <form action="{{ route('admin.voucher.update', $voucher->id) }}" method="POST">
                                @csrf

                                <div class="form-group col-md-12">
                                    <label class="control-label">Mã giảm giá</label>
                                    <input class="form-control" type="text" name="code" value="{{ old('code', $voucher->code) }}" required>
                                    @error('code') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="control-label">Phần trăm giảm (%)</label>
                                    <input class="form-control" type="number" name="discount_percent" min="1" max="100" value="{{ old('discount_percent', $voucher->discount_percent) }}" required>
                                    @error('discount_percent') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="control-label">Số lượng mã còn lại</label>
                                    <input class="form-control" type="number" name="quantity" min="0" value="{{ old('quantity', $voucher->quantity) }}" required>
                                    @error('quantity') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="control-label">Áp dụng cho đơn từ (VNĐ)</label>
                                    <input class="form-control" type="number" name="min_total" min="0" value="{{ old('min_total', $voucher->min_total) }}" required>
                                    @error('min_total') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <button class="btn btn-save" type="submit">Cập nhật</button>
                                    <a class="btn btn-cancel" href="{{ route('admin.voucher.index') }}">Quay lại</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
