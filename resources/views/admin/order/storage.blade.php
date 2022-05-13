@extends('admin_layout')
@section('admin_content')

<div class="tables">
    <div class="bs-example widget-shadow" data-example-id="hoverable-table">
        <div class="w3-res-tb mb-2 luutru">
            <a href="{{url('/all-order')}}" class="">
                <i class="fa fa-chevron-left back"></i>Quay về
            </a>
        </div>
        <h4 class="text-center text-uppercase">Danh sách đơn hủy</h4>

        <table class="table table-striped b-t b-light table-hover">
            <thead>
                <tr>
                    <th style="width:20px;">
                        <label class="i-checks m-b-none">
                            <input type="checkbox"><i></i>
                        </label>
                    </th>
                    <th>Mã đơn hàng</th>
                    <th>Ngày đặt hàng</th>
                    <th>Tình trạng đặt hàng</th>
                    <th>Lý do hủy đơn</th>
                </tr> 
            </thead>    
            <tbody> 
                @foreach($storage as $key => $val)
                <tr>
                    <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
                    <td>{{ $val->order_code }}</td>
                    <td>{{ $val->created_at }}</td>
                    <td>
                        @if($val->order_status==3)
                            <span class="text text-danger">Đơn hàng đã bị hủy</span>
                        @endif
                    </td>
                    <td>
                        {{ $val->cancel_order }}
                        <!-- <a href="{{URL::to('/restore-order/'.$val->order_id)}}" class="active " onclick="return confirm('Bạn có muốn khôi phục đơn hàng này không?')">
                            <i class="fa fa-undo" title="Khôi phục"></i>
                        </a> -->
                    </td>
                </tr>
                @endforeach
            </tbody> 
        </table>
    </div>
</div>

@endsection