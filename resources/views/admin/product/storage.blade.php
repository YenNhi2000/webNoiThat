@extends('admin_layout')
@section('admin_content')

<div class="tables">
    <div class="bs-example widget-shadow" data-example-id="hoverable-table">
        <div class="w3-res-tb mb-2 luutru">
            <a href="{{url('/all-product')}}" class="">
                <i class="fa fa-chevron-left back"></i>Quay về
            </a>
        </div>
        <h4 class="text-center text-uppercase">Danh sách lưu trữ</h4>

        <table class="table table-striped b-t b-light table-hover">
            <thead>
                <tr>
                    <th style="width:20px;">
                        <label class="i-checks m-b-none">
                            <input type="checkbox"><i></i>
                        </label>
                    </th>
                    <th>Tên sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Thư viện ảnh</th>
                    <th>Số lượng</th>
                    <th>Giá bán</th>
                    <th>Trạng thái</th>
                    <th style="width:60px;"></th>
                </tr> 
            </thead>    
            <tbody> 
                @foreach($storage as $key => $val)
                <tr>
                    <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
                    <td>{{ $val->product_name }}</td>
                    <td><img src="public/uploads/products/{{ $val->product_image }}" height="70" width="70"/></td>
                    <td><a href="{{url('/add-gallery/'.$val->product_id)}}" class="add-gallery">Thêm</a></td>
                    <td>{{ $val->product_quantity }}</td>
                    <td>
                        @if($val->product_price == 0)
                            {{ number_format($val->price_cost,0,',','.') }}
                        @elseif($val->product_price <= $val->price_cost)
                            {{ number_format($val->product_price,0,',','.') }}
                        @endif
                    </td>
                    <td>
                        <span class="text-ellipsis">
                            <?php
                                if($val->product_status == 0){
                            ?>
                                    <a href="{{URL::to('/unactive-product/'.$val->product_slug)}}"><span class="fa fa-square-o"></span></a>
                            <?php
                                }else{
                            ?>
                                    <a href="{{URL::to('/active-product/'.$val->product_slug)}}"><span class="fa fa-check-square-o"></span></a>
                            <?php
                                }
                            ?>
                        </span>
                    </td>
                    <td>
                        <a href="{{URL::to('/restore-product/'.$val->product_id)}}" class="active " onclick="return confirm('Bạn có muốn khôi phục sản phẩm này không?')">
                            <i class="fa fa-undo" title="Khôi phục"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody> 
        </table>
    </div>
</div>

@endsection