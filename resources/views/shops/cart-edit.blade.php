@extends('layouts.shops.layout')

@section('title', 'Edit Cart')

@section('content')
    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shopping__cart__table">
                        <table class="table table-bordered text-center align-middle">
                            <thead>
                                <tr>
                                    <th width="40"><input type="checkbox" id="select-all"></th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
                                    <tr>
                                        <td><input type="checkbox" class="item-checkbox" value="{{ $item->id }}"></td>
                                        <td>
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="" width="70">
                                        </td>
                                        <td class="text-left">
                                            <strong>{{ $item->product->name }}</strong><br>
                                            @if ($item->color_id)
                                                <small class="text-muted">Màu: {{ optional($item->color)->color ?? 'N/A' }}</small><br>
                                            @endif
                                            @if ($item->size)
                                                <small class="text-muted">Size: {{ $item->size }}</small>
                                            @endif
                                        </td>

                                        <td>${{ number_format($item->product->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Xoá sản phẩm này khỏi giỏ?')" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 mb-5">
                            <form id="bulk-delete-form" method="POST" action="{{ route('cart.deleteSelected') }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="selected_ids" id="selected-ids" />
                                <button type="submit" style="border: none;">Xoá sản phẩm đã chọn</button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="continue__btn">
                                <a href="{{route('cart.index')}}">Quay lại</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->
@endsection

@section('script')
    <script>
        // Tick chọn tất cả
        document.getElementById('select-all').addEventListener('change', function () {
            document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
        });

        // Khi submit form
        document.getElementById('bulk-delete-form').addEventListener('submit', function (e) {
            e.preventDefault();

            // Lấy danh sách id đã chọn
            const checked = document.querySelectorAll('.item-checkbox:checked');
            const selectedIds = Array.from(checked).map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Bạn chưa chọn sản phẩm nào!');
                return;
            }

            document.getElementById('selected-ids').value = selectedIds.join(',');
            this.submit();
        });
    </script>
@endsection

