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
        <div class="inner-sec-shop px-lg-4 px-3 ctdh">
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

                        @if($order->order_status == 2)
                        <th style="width: 200px; text-align:center">Đánh giá sản phẩm</th>
                        @endif
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
                            $subtotal = $val->product_price*$val->product_sales_qty;
                            $total += $subtotal; 
                        ?>

                    <tr class="rem1">
                        <td class="invert">{{ $i }}</td>
                        <td class="invert">{{$val->product_name}}</td>
                        <td class="invert">{{number_format($val->product_price,0,',','.')}}đ</td>   
                        <td class="invert">{{$val->product_sales_qty}}</td>
                        <td class="invert">
                            {{number_format($subtotal,0,',','.')}}đ
                        </td>   

                        @if($order->order_status == 2)
                        <td class="text-center">
                            @if(!$val->rating)
                                <button class="active cmt_rating" data-toggle="modal" data-target="#danhgia">
                                    <!-- <i class="fa fa-star"></i> -->
                                    Đánh giá
                                </button>
                            @else
                                <button class="active view_rating" data-toggle="modal" data-target="#xemdanhgia">
                                    <!-- <i class="fa fa-star-o"></i> -->
                                    Xem đánh giá
                                </button>
                            @endif
                            
                            <div id="danhgia" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <form>
                                        @csrf
                                        
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Viết đánh giá của bạn</h4>
                                            </div>
                                            <div class="modal-body">
                                            
                                            <!------Rating here---------->
                                                <ul class="list-inline rating" title="Average Rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                    
                                                    <li title="star_rating" id="{{$val->product->product_id}}-{{$i}}" data-index="{{$i}}" 
                                                        data-product_id="{{$val->product->product_id}}" style="color: #ccc;" class="rating">
                                                            &#9733;
                                                    </li>
                                                    @endfor
                                                </ul>
                                                    
                                                <form action="#">
                                                    <input type="hidden" class="ord_detail_id" value="{{$val->details_id}}"/>
                                                    <input type="hidden" name="comment_product_id" class="comment_product_id" value="{{$val->product->product_id}}">
                                                    <input type="hidden" class="comment_name" value="{{$result->customer_name}}"/>
                                                    
                                                    <textarea name="comment" class="comment_content" placeholder="Nội dung bình luận"></textarea>
                                                    <!-- <div id="notify_comment"></div> -->
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{url('/chi-tiet-don-hang/'.$val->order_code)}}" class="btn btn-default" >Đóng</a>
                                                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button> -->
                                                <button type="button" class="btn btn-default pull-right send-comment">
                                                    Gửi bình luận
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div id="xemdanhgia" class="modal fade" role="dialog">
                                <div class="modal-dialog">                                            
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Đánh giá của bạn</h4>
                                        </div>
                                        <div class="modal-body">
                                        
                                        <!------Rating here---------->
                                            <ul class="list-inline" title="Average Rating">
                                                
                                                @php
                                                    $ratingg = $val->rating;
                                                @endphp
                                                

                                                @for($i = 1; $i <= 5; $i++)
                                                    @php
                                                        if($i <= $ratingg){
                                                            $color = 'color:#ffcc00;';
                                                        }
                                                        else {
                                                            $color = 'color:#ccc;';
                                                        }
                                                    @endphp
                                                
                                                    <li title="star_rating" style="{{$color}};" class="rating"> 
                                                        &#9733; 
                                                    </li>
                                                @endfor
                                            </ul>

                                            <form> 
                                                @csrf

                                                <input type="hidden" name="cmt_detail_id" class="cmt_detail_id" value="{{$val->details_id}}">
                                                <input type="hidden" name="cmt_product_id" class="cmt_product_id" value="{{$val->product_id}}">
                                                <div id="cmt_rating"></div>
                                                <!-- <textarea name="comment" class="comment_content" placeholder="Nội dung bình luận"></textarea> -->
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @endif

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