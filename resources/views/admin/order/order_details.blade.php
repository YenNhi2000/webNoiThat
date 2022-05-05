@extends('admin_layout')
@section('admin_content')

<div class="tables">
    <div class="bs-example widget-shadow" data-example-id="hoverable-table"> 
        <div class="mb-2 luutru">
            <a href="{{url('/all-order')}}" class="">
                <i class="fa fa-chevron-left back"></i>Quay về
            </a>
        </div>
        <div class="row w3-res-tb order-detail">
            <div class="col-sm-5 order-left">
                <h5 class="text-center text-uppercase">Thông tin khách hàng</h5>
                <div class="row">
                    <div class="col-sm-5">
                        <span>Tên khách hàng :</span>
                    </div>
                    <div class="col-sm-7">{{$customer->customer_name}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <span>Số điện thoại :</span>
                    </div>
                    <div class="col-sm-7">{{$customer->customer_phone}}</div>
                </div>
            </div>
            <div class="col-sm-5 order-right">
                <h5 class="text-center text-uppercase">Thông tin giao hàng</h5>
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
        </div>

        <h4 class="text-center text-uppercase">Chi tiết đơn hàng</h4>
        
        <table class="table table-striped b-t b-light table-hover">
            <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th>Sản phẩm</th>
                    <th class="text-center">Đơn giá</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-center">Thành tiền</th>
                    <th class="text-center">Số lượng tồn kho</th>
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
                        $subtotal = $val->product_price * $val->product_sales_qty;
                        $total += $subtotal; 
                    ?>

                <tr class="rem1">
                    <td class="text-center">{{ $i }}</td>
                    <td class="invert">{{$val->product_name}}</td>
                    <td class="text-right">{{number_format($val->product_price,0,',','.')}}đ</td>   
                    <td class="text-center">{{$val->product_sales_qty}}
                        <input type="hidden" name="product_sales_quantity" class="product_sales_quantity" value="{{$val->product_sales_qty}}">
                        <input type="hidden" name="order_product_id" class="order_product_id" value="{{$val->product_id}}">
                    </td>
                    <td class="text-right">
                        {{number_format($subtotal,0,',','.')}}đ
                    </td>
                    <td class="text-center">{{$val->product->product_quantity}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>


        <div class="row">
            <div class="col-sm-4">
                @foreach($order as $key => $ord)
                    @if($ord->order_status == 1)
                        <form>
                            @csrf
                            <select class="form-control order_details">
                                <option id="{{$ord->order_id}}" value="1">Chưa xử lý</option>
                                <option id="{{$ord->order_id}}" value="2">Đã xử lý-Đã giao hàng</option>
                            </select>
                        </form>
                    @else
                        <form>
                            @csrf
                            <select class="form-control order_details">
                                <option id="{{$ord->order_id}}" value="1">Chưa xử lý</option>
                                <option id="{{$ord->order_id}}" selected value="2">Đã xử lý-Đã giao hàng</option>
                            </select>
                        </form>
                    @endif
                @endforeach
            </div>
            <div class="col-sm-4"></div>
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

        <a href="{{url('/print-order/'.$money->order_code)}}">In đơn hàng</a>
    </div>
</div>

@endsection