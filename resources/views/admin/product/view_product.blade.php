@extends('admin_layout')
@section('admin_content')

<div class="tables">
    <div class="bs-example widget-shadow" data-example-id="hoverable-table"> 
        <div class="mb-2 luutru">
            <a href="{{url('/all-product')}}" class="">
                <i class="fa fa-chevron-left back"></i>Quay về
            </a>
        </div>

        <h4 class="text-center text-uppercase">Chi tiết sản phẩm</h4>
        <div class="row ">
            <div class="col-sm-3" style="text-align:right; font-weight:bold;">
                <span>Tên sản phẩm :</span>
            </div>
            <div class="col-sm-8">{{$detail->product_name}}</div>
        </div>
        <div class="row">
            <div class="col-sm-3" style="text-align:right; font-weight:bold;">
                <span>Số lượng :</span>
            </div>
            <div class="col-sm-8">{{$detail->product_quantity}}</div>
        </div>
        <div class="row">
            <div class="col-sm-3" style="text-align:right; font-weight:bold;">
                <span>Giá sản phẩm :</span>
            </div>
            <div class="col-sm-8">
                @if($detail->product_price == 0)
                    {{ number_format($detail->price_cost,0,',','.') }}đ
                @elseif($detail->product_price <= $detail->price_cost)
                    {{ number_format($detail->product_price,0,',','.') }}đ
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3" style="text-align:right; font-weight:bold;">
                <span>Mô tả :</span>
            </div>
            <div class="col-sm-8">{!!$detail->product_desc!!}</div>
        </div>
        <div class="row">
            <div class="col-sm-3" style="text-align:right; font-weight:bold;">
                <span>Hướng dẫn bảo quản :</span>
            </div>
            <div class="col-sm-8">{!!$detail->product_content!!}</div>
        </div>
        <div class="row">
            <div class="col-sm-3" style="text-align:right; font-weight:bold;">
                <span>Danh mục :</span>
            </div>
            <div class="col-sm-8">{{$detail->category_name}}</div>
        </div>
        <div class="row">
            <div class="col-sm-3" style="text-align:right; font-weight:bold;">
                <span>Thương hiệu :</span>
            </div>
            <div class="col-sm-8">{{$detail->brand_name}}</div>
        </div>
        <div class="row">
            <div class="col-sm-3" style="text-align:right; font-weight:bold;">
                <span>Loại sản phẩm :</span>
            </div>
            <div class="col-sm-8">{{$detail->type_name}}</div>
        </div>
    </div>
</div>

@endsection