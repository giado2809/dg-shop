@extends('layouts.admins.layout-admin')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<main class="app-content">
  <div class="app-title">
      <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><a href="{{route('admin.product.index')}}">Danh sách sản phẩm</a></li>
          <li class="breadcrumb-item active"><a href="#"><b>Chi tiết sản phẩm #{{ $product->id }}</b></a></li>
      </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <div class="tile-body">
          <h4>{{ $product->name }}</h4>
          <p><strong>Danh mục:</strong> {{ $product->category->name ?? 'Không có' }}</p>
          <p><strong>Tag:</strong>
            @if($product->tag == null)
            Không có
            @else
              {{ $product->tag }}</p>
            @endif
          <p><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }}đ</p>
          <p><strong>Giá khuyến mãi:</strong>
            @if($product->sale_price == null)
            Không có
            @else
            {{ number_format($product->sale_price, 0, ',', '.') }}đ
            @endif
          </p>
          <p><strong>Số lượng:</strong> {{ $product->total_quantity }}</p>
          <p><strong>Mô tả:</strong>
            @if($product->description == null)
              Không có
            @else
              <div>{!! $product->description !!}</div>
            @endif
          </p>
          <p><strong>Ảnh đại diện:</strong></p>
          <img src="{{ asset('storage/' . $product->image) }}" width="200">

          <hr>
          <h5>Phân loại:</h5>
          @forelse ($product->colors as $color)
            <div class="mb-4" style="border-bottom: 1px solid #ccc;">
              <strong>Màu:</strong> {{ $color->color }}<br>
              <img src="{{ asset('storage/' . $color->image) }}" width="80" class="mb-2"><br>
              <ul>
                @foreach ($color->sizes as $size)
                  <li>
                    Size: <strong>{{ $size->size }}</strong> - 
                    Số lượng: <strong>{{ $size->quantity }}</strong>
                    @if ($size->quantity == 0)
                      <span class="text-danger">(Hết)</span>
                    @endif
                  </li>
                @endforeach
              </ul>
            </div>
          @empty
            <p>Không có biến thể</p>
          @endforelse

          <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-primary mt-3">
              <i class="fas fa-edit"></i> Sửa sản phẩm
          </a>
          <a href="{{ route('admin.product.index', $product->id) }}" class="btn btn-primary mt-3">
            Quay lại
          </a>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
