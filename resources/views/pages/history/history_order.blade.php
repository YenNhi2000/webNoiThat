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
                
                <li>Lịch sử đơn hàng</li>
            </ul>
        </div>
    </div>
</div>
<!--//banner -->

<section class="banner-bottom-wthreelayouts py-3">
    <div class="container-fluid">
        <div class="inner-sec-shop px-lg-4 px-3">
            <h3 class="tittle-w3layouts my-lg-4 mt-4">Lịch sử đơn hàng</h3>

            <table class="table table-striped b-t b-light table-hover">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt hàng</th>
                        <th>Tình trạng đặt hàng</th>
                        <th style="width: 300px;"></th>
                    </tr> 
                </thead>    
                <tbody class="staffList"> 
                    @php
                        $i = 0;
                    @endphp

                    @foreach($order as $key => $ord)
                        @php
                            $i++;
                        @endphp
                    
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $ord->order_code }}</span></td>
                        <td>{{ $ord->created_at }}</td>
                        <td>
                            @if($ord->order_status==1)
                                <span class="text text-success">Đơn hàng mới</span>
                            @elseif($ord->order_status==2)
                                <span class="text text-primary">Đã xử lý - Đã giao hàng</span>
                            @else
                                <span class="text text-danger">Đơn hàng đã bị hủy</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{URL::to('/chi-tiet-don-hang/'.$ord->order_code)}}" class="btn btn-primary active" ui-toggle-class="">
                                Xem chi tiết
                            </a>
                            
                            @if($ord->order_status == 1)

                            <!-- Trigger the modal with a button -->
                            <button type="button" class="btn btn-danger destroy" data-toggle="modal" data-target="#huydon">Hủy đơn hàng</button>
                            @endif
                            <!-- Modal -->
                            <div id="huydon" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <form>
                                        @csrf
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Lý do hủy đơn hàng</h4>
                                            </div>
                                            <div class="modal-body">
                                                <textarea rows="5" class="lydohuydon" required placeholder="Lý do hủy đơn hàng....(bắt buộc)"></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                                <button type="button" id="{{ $ord->order_code }}" onclick="huydonhang(this.id)" class="btn btn-success">Gửi</button>    <!-- this.id (id=ord->order_code) -->
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- <a href="{{URL::to('/delete-order/'.$ord->order_id)}}" class="active delete" onclick="return confirm('Bạn có muốn xóa đơn hàng này không?')">
                                <i class="fa fa-times text-danger text" title="Xóa"></i>
                            </a> -->
                        </td>
                    </tr>
                    @endforeach
                </tbody> 
            </table>
        </div>
    </div>
</section>

@endsection