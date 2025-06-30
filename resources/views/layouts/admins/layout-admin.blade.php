<!DOCTYPE html>
<html lang="en">

<head>
  <title>@yield('title')</title>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" type="text/css" href="{{ asset('admins/css/css/main.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  
  <script src="http://code.jquery.com/jquery.min.js" type="text/javascript"></script>
  <script type="text/javascript" src="{{ asset('admins/ckeditor/ckeditor.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  @yield('style')
</head>

<body onload="time()" class="app sidebar-mini rtl">
  <!-- Navbar-->
  <header class="app-header">
      <!-- Sidebar toggle button-->
      <a class="app-sidebar__toggle" href="#" data-toggle="sidebar"aria-label="Hide Sidebar"></a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
          <!-- User Menu-->
          <li><a href="{{route('index')}}" class="app-nav__item bx">Shop</a></li>
          <li><a class="app-nav__item" href="{{route('login')}}"><i class='bx bx-log-out bx-rotate-180'></i> </a>
          </li>
      </ul>
  </header>
 
  <!-- Sidebar menu-->
  <div class="app-sidebar__overlay" data-toggle="sidebar"></div>

  <aside class="app-sidebar">
      <div class="app-sidebar__user">
        <img class="app-sidebar__user-avatar"
        src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('admins/images/default-avatar.png') }}"
        width="50px" alt="User Image">

        <div>
          @if (Auth::check())
            <p class="app-sidebar__user-name"><b>{{ Auth::user()->name }}</b></p>
          @endif
          <p class="app-sidebar__user-designation">Chào mừng bạn trở lại</p>
        </div>
      </div>
      <hr>
      <ul class="app-menu">
          <li><a class="app-menu__item " href="{{route('admin.dashboard')}}">
              <i class='app-menu__icon bx bx-home'></i>
              <span class="app-menu__label">Trang chủ</span>
              </a>
          </li> 
          <li>
          <a class="app-menu__item" href="{{route('admin.category.index')}}">
              <i class='app-menu__icon bx bx-category'></i>
              <span class="app-menu__label">Quản lý danh mục</span>
          </a>
          </li>
          <li>
          <a class="app-menu__item" href="{{route('admin.product.index')}}">
              <i class='app-menu__icon bx bx-purchase-tag-alt'></i>
              <span class="app-menu__label">Quản lý sản phẩm</span>
          </a>
          </li>
          <li>
          <a class="app-menu__item " href="{{route('admin.user.index')}}">
              <i class='app-menu__icon bx bx-id-card'></i>
              <span class="app-menu__label">Quản lý người dùng</span>
          </a>
          </li>         
          <li>
          <a class="app-menu__item" href="{{route('admin.order.index')}}">
              <i class='app-menu__icon bx bx-task'></i>
              <span class="app-menu__label">Quản lý đơn hàng</span>
          </a>
          </li>
          <li>
          <a class="app-menu__item" href="{{route('admin.review.index')}}">
              <i class='app-menu__icon bx bx-comment-detail'></i>
              <span class="app-menu__label">Quản lý bình luận</span>
          </a>
          </li>
          <li>
          <a class="app-menu__item" href="{{route('admin.voucher.index')}}">
              <i class='app-menu__icon bx bx-gift'></i>
              <span class="app-menu__label">Quản lý voucher</span>
          </a>
          </li>
      </ul>
  </aside>

  <main>
    @yield('content')
  </main>
  
  <script src="{{ asset('admins/js/js/jquery-3.2.1.min.js') }}"></script>
  <script src="{{ asset('admins/js/js/popper.min.js') }}"></script>
  <script src="{{ asset('admins/js/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('admins/js/main.js') }}"></script>
  <script src="{{ asset('admins/js/js/plugins/pace.min.js') }}"></script> 
  <script type="text/javascript" src="admins/js/js/plugins/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="admins/js/js/plugins/dataTables.bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  <script src="admins/js/js/main.js"></script>
  <script src="src/jquery.table2excel.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript">$('#sampleTable').DataTable();</script>
  <script>
    //In dữ liệu
    var myApp = new function () {
      this.printTable = function () {
        var tab = document.getElementById('sampleTable');
        var win = window.open('', '', 'height=700,width=700');
        win.document.write(tab.outerHTML);
        win.document.close();
        win.print();
      }
    }
  </script>
  <script>
    // Tick chọn tất cả
    document.getElementById('check-all').addEventListener('change', function () {
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
    });

    // Xử lý submit xóa nhiều
    document.getElementById('bulk-delete-form').addEventListener('submit', function (e) {
        const checked = document.querySelectorAll('.item-checkbox:checked');
        const selectedIds = Array.from(checked).map(cb => cb.value);

        if (selectedIds.length === 0) {
            e.preventDefault();
            alert('Bạn chưa chọn sản phẩm nào!');
            return;
        }

        document.getElementById('selected-ids').value = selectedIds.join(',');
    });

    // Xử lý xóa lẻ
    document.querySelectorAll('.delete-single').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
                document.getElementById('selected-ids').value = id;
                document.getElementById('bulk-delete-form').submit();
            }
        });
    });
  </script>
  <script>
    function readURL(input, thumbimage) {
      if (input.files && input.files[0]) { //Sử dụng  cho Firefox - chrome
        var reader = new FileReader();
        reader.onload = function (e) {
          $("#thumbimage").attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
      else { // Sử dụng cho IE
        $("#thumbimage").attr('src', input.value);
      }
      $("#thumbimage").show();
      $('.filename').text($("#uploadfile").val());
      $('.Choicefile').css('background', '#14142B');
      $('.Choicefile').css('cursor', 'default');
      $(".removeimg").show();
      $(".Choicefile").unbind('click');
    }
    $(document).ready(function () {
      $(".Choicefile").bind('click', function () {
        $("#uploadfile").click();

      });
      $(".removeimg").click(function () {
        $("#thumbimage").attr('src', '').hide();
        $("#myfileupload").html('<input type="file" id="uploadfile"  onchange="readURL(this);" />');
        $(".removeimg").hide();
        $(".Choicefile").bind('click', function () {
          $("#uploadfile").click();
        });
        $('.Choicefile').css('background', '#14142B');
        $('.Choicefile').css('cursor', 'pointer');
        $(".filename").text("");
      });
    })
  </script>
  <script type="text/javascript">
      //Thời Gian
      function time() {
        var today = new Date();
        var weekday = new Array(7);
        weekday[0] = "Chủ Nhật";
        weekday[1] = "Thứ Hai";
        weekday[2] = "Thứ Ba";
        weekday[3] = "Thứ Tư";
        weekday[4] = "Thứ Năm";
        weekday[5] = "Thứ Sáu";
        weekday[6] = "Thứ Bảy";
        var day = weekday[today.getDay()];
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        nowTime = h + " giờ " + m + " phút " + s + " giây";
        if (dd < 10) {
          dd = '0' + dd
        }
        if (mm < 10) {
          mm = '0' + mm
        }
        today = day + ', ' + dd + '/' + mm + '/' + yyyy;
        tmp = '<span class="date"> ' + today + ' - ' + nowTime +
          '</span>';
        document.getElementById("clock").innerHTML = tmp;
        clocktime = setTimeout("time()", "1000", "Javascript");

        function checkTime(i) {
          if (i < 10) {
            i = "0" + i;
          }
          return i;
        }
      }
  </script>
  @yield('script')

</body>

</html>