@extends('layouts.admins.layout-admin')

@section('title', 'Dashboard')

@section('content')
  <main class="app-content">
    
      <div class="row">
        <div class="col-md-12">
          <div class="app-title">
            <ul class="app-breadcrumb breadcrumb">
              <li class="breadcrumb-item"><a href="#"><b>Bảng điều khiển</b></a></li>
            </ul>
            <div id="clock"></div>
          </div>
        </div>
      </div>

      <div class="row">

        <!--Left-->
        <div class="col-md-12 col-lg-6">
          <div class="row">

            <!-- col-6 -->
            <div class="col-md-6">
              <div class="widget-small primary coloured-icon">
                <i class='icon bx bxs-user-account fa-3x'></i>
                <div class="info">
                  <h4>Tổng khách hàng</h4>
                  <p><b>{{ $totalUsers }} khách hàng</b></p>
                  <p class="info-tong">Tổng số khách hàng được quản lý.</p>
                </div>
              </div>
            </div>

            <!-- col-6 -->
            <div class="col-md-6">
              <div class="widget-small info coloured-icon"><i class='icon bx bxs-data fa-3x'></i>
                <div class="info">
                  <h4>Tổng sản phẩm</h4>
                  <p><b>{{ $totalProducts }} sản phẩm</b></p>
                  <p class="info-tong">Tổng số sản phẩm được quản lý.</p>
                </div>
              </div>
            </div>

            <!-- col-6 -->
            <div class="col-md-6">
              <div class="widget-small warning coloured-icon"><i class='icon bx bxs-shopping-bags fa-3x'></i>
                <div class="info">
                  <h4>Tổng đơn hàng</h4>
                  <p><b>{{ $totalOrders }} đơn hàng</b></p>
                  <p class="info-tong">Tổng số hóa đơn bán hàng trong tháng.</p>
                </div>
              </div>
            </div>

            <!-- col-6 -->
            <div class="col-md-6">
              <div class="widget-small danger coloured-icon"><i class='icon bx bxs-error-alt fa-3x'></i>
                <div class="info">
                  <h4>Tổng doanh thu</h4>
                  <p><b>{{ number_format($totalRevenue, 0, ',', '.') }}đ</b></p>
                  <p class="info-tong">Tổng doanh thu bán hàng trong tháng.</p>
                </div>
              </div>
            </div>

            <!-- col-12 -->
            <div class="col-md-12">
                <div class="tile">
                  <h3 class="tile-title">Top sản phẩm bán chạy</h3>
                <div>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Danh mục</th>
                        <th>Đã bán</th>
                        <th>Doanh thu</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($topProducts as $product)
                        <tr>
                          <td>#{{ $product->id }}</td>
                          <td>{{ Str::limit($product->product_name, 50) }}</td>
                          <td>{{ $product->category_name }}</td>
                          <td><span class="tag tag-success">{{ $product->total_sold }}</span></td>
                          <td>{{ number_format($product->total_revenue, 0, ',', '.') }}đ</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

              </div>
            </div>
            <!-- / col-12 -->

            <!-- col-12 -->
            <div class="col-md-12">
              <div class="tile">
                <h3 class="tile-title">Đơn hàng mới</h3>
                <div>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Tên khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($latestOrders as $order)
                        <tr>
                          <td>#{{ $order->id }}</td>
                          <td>{{ $order->user->name ?? 'Ẩn danh' }}</td>
                          <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                          <td>
                            @php
                              $statusColor = match($order->status) {
                                'pending' => 'info',
                                'processing' => 'warning',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                default => 'secondary'
                              };
                            @endphp
                            <span class="badge bg-{{ $statusColor }}">
                              {{ ucfirst(__($order->status)) }}
                            </span>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!--END left-->

        <!--Right-->
        <div class="col-md-12 col-lg-6">
          <div class="row">
            <div class="col-md-12">
              <div class="tile">
                <h3 class="tile-title">Dữ liệu 6 tháng đầu vào</h3>
                <div class="embed-responsive embed-responsive-16by9">
                  <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="tile">
                <h3 class="tile-title">Thống kê 6 tháng doanh thu</h3>
                <div class="embed-responsive embed-responsive-16by9">
                  <canvas class="embed-responsive-item" id="barChartDemo"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--END right-->

      </div>

      <div class="text-center" style="font-size: 13px">
        <p><b>Copyright
            <script type="text/javascript">
              document.write(new Date().getFullYear());
            </script> Template phần mềm quản lý bán hàng | Dev By Trường
          </b></p>
      </div>
  </main>
@endsection

@section('script')
  <script src="{{ asset('admins/js/js/jquery-3.2.1.min.js') }}"></script>
  <!--===============================================================================================-->
  <script src="{{ asset('admins/js/js/popper.min.js') }}"></script>
  <script src="https://unpkg.com/boxicons@latest/dist/boxicons.js"></script>
  <!--===============================================================================================-->
  <script src="{{ asset('admins/js/js/bootstrap.min.js') }}"></script>
  <!--===============================================================================================-->
  <script src="{{ asset('admins/js/js/main.js') }}"></script>
  <!--===============================================================================================-->
  <script src="{{ asset('admins/js/js/plugins/pace.min.js') }}"></script>
  <!--===============================================================================================-->
  <script type="text/javascript" src="{{ asset('admins/js/js/plugins/chart.js') }}"></script>
  <!--===============================================================================================-->
  <script type="text/javascript">
    var data = {
      labels: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6"],
      datasets: [{
        label: "Dữ liệu đầu tiên",
        fillColor: "rgba(255, 213, 59, 0.767), 212, 59)",
        strokeColor: "rgb(255, 212, 59)",
        pointColor: "rgb(255, 212, 59)",
        pointStrokeColor: "rgb(255, 212, 59)",
        pointHighlightFill: "rgb(255, 212, 59)",
        pointHighlightStroke: "rgb(255, 212, 59)",
        data: [20, 59, 90, 51, 56, 100]
      },
      {
        label: "Dữ liệu kế tiếp",
        fillColor: "rgba(9, 109, 239, 0.651)  ",
        pointColor: "rgb(9, 109, 239)",
        strokeColor: "rgb(9, 109, 239)",
        pointStrokeColor: "rgb(9, 109, 239)",
        pointHighlightFill: "rgb(9, 109, 239)",
        pointHighlightStroke: "rgb(9, 109, 239)",
        data: [48, 48, 49, 39, 86, 10]
      }
      ]
    };
    var ctxl = $("#lineChartDemo").get(0).getContext("2d");
    var lineChart = new Chart(ctxl).Line(data);

    var ctxb = $("#barChartDemo").get(0).getContext("2d");
    var barChart = new Chart(ctxb).Bar(data);
  </script>
  <script type="text/javascript">$('#sampleTable').DataTable();</script>
@endsection