@extends('admin_layout')
@section('admin_content')

<div class="tables dashboard">
    <!-- <h5 class="text-center text-uppercase">Thống kê doanh số</h5> -->
    <div class="row">
        <div class="bs-example widget-shadow col-md-12">
            <div class="row"> 
                <form autocomplete="off">
                    @csrf

                    <div class="col-md-2">
                        <p>Từ ngày: <input type="text" id="datepicker1" class="form-control"></p>
                        <input type="button" id="btn-filter" class="lockq" value="Lọc kết quả"></p>
                    </div>

                    <div class="col-md-2">
                        <p>Đến ngày: <input type="text" id="datepicker2" class="form-control"></p>
                    </div>

                    <div class="col-md-2">
                        <p>Lọc theo: 
                            <select class="dashboard-filter form-control" >
                                <option>--Chọn--</option>
                                <option value="7ngay">7 ngày qua</option>
                                <option value="thangtruoc">Tháng trước</option>
                                <option value="thangnay">Tháng này</option>
                                <option value="365ngayqua">365 ngày qua</option>
                            </select>
                        </p>
                    </div>
                </form>
            </div>

            <div class="col-md-12">
                <div id="chart" style="height: 250px;"></div>
            </div>
        </div>

        <!-- <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="r3_counter_box widget2">
                        <i class="pull-left fa fa-dollar icon-rounded"></i>
                        <div class="stats">
                            <h5><strong>$452</strong></h5>
                            <span>Total Revenue</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="r3_counter_box widget2">
                        <i class="pull-left fa fa-dollar icon-rounded"></i>
                        <div class="stats">
                            <h5><strong>$452</strong></h5>
                            <span>Total Revenue</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="r3_counter_box widget2">
                        <i class="pull-left fa fa-dollar icon-rounded"></i>
                        <div class="stats">
                            <h5><strong>$452</strong></h5>
                            <span>Total Revenue</span>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
    <!-- <div class="row"></div> -->
    <div class="row">
        <div class="col-md-3 widget widget1">
            <div class="r3_counter_box">
                <i class="pull-left fa fa-tasks icon-rounded"></i>
                <div class="stats">
                    <h5><strong>{{$app_product}}</strong></h5>
                    <span>Sản phẩm</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 widget widget1">
            <div class="r3_counter_box">
                <i class="pull-left fa fa-file-text-o dollar1 icon-rounded"></i>
                <div class="stats">
                    <h5><strong>{{$app_order}}</strong></h5>
                    <span>Đơn hàng</span>
                </div>
            </div>
        </div><div class="col-md-3 widget widget1">
            <div class="r3_counter_box">
                <i class="pull-left fa fa-users dollar2 icon-rounded"></i>
                <div class="stats">
                    <h5><strong>{{$app_customer}}</strong></h5>
                    <span>Khách hàng</span>
                </div>
            </div>
        </div><div class="col-md-3 widget">
            <div class="r3_counter_box">
                <i class="pull-left fa fa-comments user2 icon-rounded"></i>
                <div class="stats">
                    <h5><strong>{{$app_comment}}</strong></h5>
                    <span>Bình luận</span>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="row">
        <div class="col-md-4 col-xs-12">
            <!-- <p class="title_thongke">Thống kê tổng sản phẩm bài viết đơn hàng</p> 
            <div id="donut"></div>	
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-7 col-xs-12">
            <style type="text/css">
                ol.list_views {
                    margin: 10px 0;
                    color: #333333;
                }
                ol.list_views li{
                    padding-bottom: 7px;
                    letter-spacing: 0.2px;
                }
                ol.list_views a {
                    color: orange;
                    font-weight: 400;
                }
            </style>
            <h3>Sản phẩm xem nhiều</h3>

            <ol class="list_views">
                @foreach($product_views as $key => $pro)
                <li>
                    <a target="_blank" href="{{url('/chi-tiet/'.$pro->product_slug)}}">{{$pro->product_name}} | <span style="color:black">{{$pro->product_views}}</span></a>
                </li>
                @endforeach
            </ol>
        </div>
    </div> -->
</div>

@endsection