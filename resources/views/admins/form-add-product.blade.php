@extends('layouts.admins.layout-admin')

@section('title', 'Tạo mới sản phẩm')

@section('style')
  <style>
    .Choicefile {
      display: block;
      background: #14142B;
      border: 1px solid #fff;
      color: #fff;
      width: 150px;
      text-align: center;
      text-decoration: none;
      cursor: pointer;
      padding: 5px 0px;
      border-radius: 5px;
      font-weight: 500;
      align-items: center;
      justify-content: center;
    }

    .Choicefile:hover {
      text-decoration: none;
      color: white;
    }

    #uploadfile,
    .removeimg {
      display: none;
    }

    #thumbbox {
      position: relative;
      width: 100%;
      margin-bottom: 20px;
    }

    .removeimg {
      height: 25px;
      position: absolute;
      background-repeat: no-repeat;
      top: 5px;
      left: 5px;
      background-size: 25px;
      width: 25px;
      /* border: 3px solid red; */
      border-radius: 50%;

    }

    .removeimg::before {
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
      content: '';
      border: 1px solid red;
      background: red;
      text-align: center;
      display: block;
      margin-top: 11px;
      transform: rotate(45deg);
    }

    .removeimg::after {
      /* color: #FFF; */
      /* background-color: #DC403B; */
      content: '';
      background: red;
      border: 1px solid red;
      text-align: center;
      display: block;
      transform: rotate(-45deg);
      margin-top: -2px;
    }
  </style>
@endsection

@section('content')
  <main class="app-content">
    <div class="app-title">
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.product.index')}}">Danh sách sản phẩm</a></li>
        <li class="breadcrumb-item">Thêm sản phẩm</li>
      </ul>
      <div id="clock"></div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          <h3 class="tile-title">Tạo mới sản phẩm</h3>
          <div class="tile-body">
            <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" class="row">
              @csrf
              <div class="form-group col-md-3">
                <label class="control-label">Tên sản phẩm</label>
                <input class="form-control" type="text" name="name" required>
              </div>
              <div class="form-group col-md-3">
                <label for="exampleSelect1" class="control-label">Danh mục</label>
                <select class="form-control" id="exampleSelect1" name="category_id">
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-3">
                <label class="control-label">Giá bán</label>
                <input class="form-control" type="number" name="price" required>
              </div>
              <div class="form-group col-md-3">
                <label class="control-label">Giá khuyến mãi (nếu có)</label>
                <input class="form-control" type="number" name="sale_price" step="0.01" min="0">
              </div>
              <div class="form-group col-md-3 ">
                <label for="exampleSelect1" class="control-label">Tag sản phẩm</label>
                <select class="form-control" id="exampleSelect1" name="tag">
                  <option value="">Không có</option>
                  <option value="hot-sales">Sale</option>
                  <option value="new-arrivals">New</option>
                </select>
              </div>
              <div class="form-group col-md-12">
                <label class="control-label">Ảnh sản phẩm</label>
                <div id="myfileupload">
                  <input type="file" id="uploadfile" name="image" onchange="readURL(this);" required/>
                </div>
                <div id="thumbbox">
                  <img height="450" width="400" alt="Thumb image" id="thumbimage" style="display: none" />
                  <a class="removeimg" href="javascript:"></a>
                </div>
                <div id="boxchoice">
                  <a href="javascript:" class="Choicefile"><i class="fas fa-cloud-upload-alt"></i> Chọn ảnh</a>
                  <p style="clear:both"></p>
                </div>
              </div>
              <div class="form-group col-md-12">
                <label class="control-label">Phân loại (màu + size + số lượng)</label>
                <div id="color-section">
                  <div class="color-block mb-4">
                    <div class="row mb-2">
                      <div class="col-md-4">
                        <input type="text" name="colors[0][color]" class="form-control" placeholder="Tên màu (VD: Đỏ)" required>
                      </div>
                      <div class="col-md-4">
                        <input type="file" name="colors[0][image]" class="form-control" required>
                      </div>
                    </div>
                    <div class="size-section">
                      <div class="row mb-2">
                        <div class="col-md-3">
                          <input type="text" name="colors[0][sizes][0][size]" class="form-control" placeholder="Size (VD: M)" required>
                        </div>
                        <div class="col-md-3">
                          <input type="number" name="colors[0][sizes][0][quantity]" class="form-control" placeholder="Số lượng" required>
                        </div>
                      </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="addSize(this)">+ Thêm size</button>
                  </div>
                </div>
                <button type="button" class="btn btn-primary mt-3" onclick="addColor()">+ Thêm màu</button>
              </div>
              <div class="form-group col-md-12">
                <label class="control-label">Mô tả sản phẩm</label>
                <textarea class="form-control" name="description" id="mota"></textarea>
                <script>CKEDITOR.replace('mota');</script>
              </div>
              <div class="form-group col-md-12">
                <button class="btn btn-save" type="submit">Lưu</button>
                <a class="btn btn-cancel" href="{{route('admin.product.index')}}">Quay lại</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@section('script')
  <script>
    let colorIndex = 1;
    let sizeCounters = {};

    function addColor() {
      const html = `
        <div class="color-block mb-4">
          <div class="row mb-2">
            <div class="col-md-4">
              <input type="text" name="colors[${colorIndex}][color]" class="form-control" placeholder="Tên màu" required>
            </div>
            <div class="col-md-4">
              <input type="file" name="colors[${colorIndex}][image]" class="form-control" required>
            </div>
          </div>
          <div class="size-section">
            <div class="row mb-2">
              <div class="col-md-3">
                <input type="text" name="colors[${colorIndex}][sizes][0][size]" class="form-control" placeholder="Size" required>
              </div>
              <div class="col-md-3">
                <input type="number" name="colors[${colorIndex}][sizes][0][quantity]" class="form-control" placeholder="Số lượng" required> 
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-sm btn-secondary" onclick="addSize(this)">+ Thêm size</button>
        </div>
      `;
      document.getElementById("color-section").insertAdjacentHTML("beforeend", html);
      sizeCounters[colorIndex] = 1;
      colorIndex++;
    }

    function addSize(button) {
      const colorBlock = button.closest('.color-block');
      const input = colorBlock.querySelector("input[name*='colors']");
      const colorIndexMatch = input.name.match(/colors\[(\d+)\]/);
      const colorIndex = colorIndexMatch ? parseInt(colorIndexMatch[1]) : 0;

      if (!sizeCounters[colorIndex]) sizeCounters[colorIndex] = 1;
      const sizeIndex = sizeCounters[colorIndex];

      const sizeSection = colorBlock.querySelector('.size-section');

      const html = `
        <div class="row mb-2">
          <div class="col-md-3">
            <input type="text" name="colors[${colorIndex}][sizes][${sizeIndex}][size]" class="form-control" placeholder="Size" required>
          </div>
          <div class="col-md-3">
            <input type="number" name="colors[${colorIndex}][sizes][${sizeIndex}][quantity]" class="form-control" placeholder="Số lượng" required>
          </div>
        </div>
      `;
      sizeSection.insertAdjacentHTML("beforeend", html);
      sizeCounters[colorIndex]++;
    }
  </script>
@endsection
