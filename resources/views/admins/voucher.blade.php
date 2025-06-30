@extends('layouts.admins.layout-admin')

@section('title', 'Danh sách mã giảm giá')

@section('content')
<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Danh sách mã giảm giá</b></a></li>
        </ul>
        <div id="clock"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="row element-button">
                        <div class="col-sm-2">
                            <a class="btn btn-add btn-sm" href="{{ route('admin.voucher.create') }}" title="Thêm"><i class="fas fa-plus"></i> Tạo mới mã</a>
                        </div>
                        <div class="col-sm-2">
                            <a class="btn btn-delete btn-sm print-file" type="button" title="In" onclick="myApp.printTable()"><i class="fas fa-print"></i> In dữ liệu</a>
                        </div>
                    </div>
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                <th width="10"><input type="checkbox" id="check-all"></th>
                                <th>ID</th>
                                <th>Mã</th>
                                <th>% Giảm</th>
                                <th>Số lượng còn</th>
                                <th>Áp dụng từ</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vouchers as $voucher)
                            <tr>
                                <td><input type="checkbox" class="item-checkbox" value="{{ $voucher->id }}"></td>
                                <td>{{ $voucher->id }}</td>
                                <td><strong>{{ $voucher->code }}</strong></td>
                                <td>{{ $voucher->discount_percent }}%</td>
                                <td>{{ $voucher->quantity }}</td>
                                <td>{{ number_format($voucher->min_total, 0, ',', '.') }}đ</td>
                                <td>
                                    <form action="{{ route('admin.voucher.destroy', $voucher->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn chắc chắn muốn xoá mã này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    <a href="{{route('admin.voucher.edit', $voucher->id)}}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>    
                    <form id="bulk-delete-form" action="{{ route('admin.voucher.bulkDelete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" id="selected-ids">
                        <button type="submit" class="btn btn-danger mt-3">Xoá mã đã chọn</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
    <script>
        document.getElementById('check-all').addEventListener('change', function () {
            let checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        document.getElementById('bulk-delete-form').addEventListener('submit', function (e) {
            let ids = [];
            document.querySelectorAll('.item-checkbox:checked').forEach(cb => ids.push(cb.value));

            if (ids.length === 0) {
                e.preventDefault();
                alert('Bạn chưa chọn mã nào để xoá!');
            } else {
                document.getElementById('selected-ids').value = ids.join(',');
            }
        });
    </script>

@endsection