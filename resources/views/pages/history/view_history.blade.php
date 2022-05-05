@extends('layout')
@section('content')

<!-- banner -->
<div class="banner_inner">
    <div class="services-breadcrumb">
        <div class="inner_breadcrumb">
            <ul class="short">
                <li>
                    <a href="{{URL::to('/')}}">Trang chủ</a>
                    <i>|</i>
                </li>
                
                <li>Xem chi tiết đơn hàng</li>
            </ul>
        </div>
    </div>
</div>
<!--//banner -->

<section class="banner-bottom-wthreelayouts py-3">
    <div class="container-fluid">
        <div class="inner-sec-shop px-lg-4 px-3">
            <div class="mb-2 luutru">
                <a href="{{url('/lich-su-don-hang')}}">
                    <i class="fa fa-chevron-left"></i>Quay về
                </a>
            </div>
        
            <h3 class="tittle-w3layouts my-lg-4 mt-4 text-center">Chi tiết đơn hàng</h3>
        
            <div class="order-detail">
                <div class="row">
                    <div class="col-sm-5">
                        <span>Tên người nhận :</span>
                    </div>
                    <div class="col-sm-7">{{$shipping->shipping_name}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <span>Số điện thoại :</span>
                    </div>
                    <div class="col-sm-7">{{$shipping->shipping_phone}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <span>Địa chỉ :</span>
                    </div>
                    <div class="col-sm-7">{{$shipping->shipping_address}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <span>Ghi chú :</span>
                    </div>
                    <div class="col-sm-7">{{$shipping->shipping_notes}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <span>Phương thức thanh toán:</span>
                    </div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-7">
                        @if($payment->payment_method == 1)
                            Thẻ tín dụng
                        @else
                            Thanh toán khi nhận hàng
                        @endif
                    </div>
                </div>
            </div>

            <table class="table table-striped b-t b-light table-hover">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th style="width: 200px; text-align:center">Đánh giá sản phẩm</th>
                    </tr>
                </thead>
                <tbody>
                        <?php
                            $i = 0;
                            $total = 0;
                        ?>

                    @foreach($order_details as $key => $val)
                        
                        <?php
                            $i++;
                            $subtotal = $val->product_price*$val->product_quantity;
                            $total += $subtotal; 
                        ?>

                    <tr class="rem1">
                        <td class="invert">{{ $i }}</td>
                        <td class="invert">{{$val->product_name}}</td>
                        <td class="invert">{{number_format($val->product_price,0,',','.')}}đ</td>   
                        <td class="invert">{{$val->product_quantity}}</td>
                        <td class="invert">
                            {{number_format($subtotal,0,',','.')}}đ
                        </td>                        
                        <td class="text-center">
                            @if ($cus_rating == null)
                                <a href="{{URL::to('/chi-tiet-san-pham/'.$val->order_code.'/'.$val->product->product_slug)}}" class="active view" ui-toggle-class="">
                                    Đánh giá
                                </a>
                            @else
                                <a href="{{URL::to('/chi-tiet-san-pham/'.$val->order_code.'/'.$val->product->product_slug)}}" class="active view" ui-toggle-class="">
                                    Đã đánh giá
                                </a>
                            @endif
                        </td>
                    </tr> 
                    @endforeach
                </tbody>
            </table>

            <div class="row">
                <div class="col-sm-8"></div>
                <div class="col-sm-2">
                    <label>Tổng tiền hàng :</label>
                </div>
                <div class="col-sm-2">
                    {{number_format($total,0,',','.')}}đ
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8"></div>
                <div class="col-sm-2">
                    <label>Voucher của shop :</label>
                </div>
                <div class="col-sm-2">
                    @if($coupon->coupon_condition == 1)
                        {{$coupon->coupon_number}}%
                    @else
                        {{number_format($coupon->coupon_number,0,',','.')}}đ
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8"></div>
                <div class="col-sm-2">
                    <label>Tổng tiền :</label>
                </div>
                <div class="col-sm-2">
                    {{number_format($money->order_total,0,',','.')}}đ
                </div>
            </div>
        </div>
    </div>
</section>

@endsection