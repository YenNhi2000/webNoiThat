@extends('admin_layout')
@section('admin_content')

<div class="tables">
    <div class="bs-example widget-shadow" data-example-id="hoverable-table">
        <div class="w3-res-tb mb-2 luutru">
            <a href="{{url('/all-coupon')}}" class="">
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
                    <th>Tên mã giảm giá</th>
                    <th class="coupon">Mã</th>
                    <th class="coupon">Ngày bắt đầu</th>
                    <th class="coupon">Ngày kết thúc</th>
                    <th class="coupon">Số lượng</th>
                    <th class="coupon">Số giảm</th>
                    <th class="coupon">Tình trạng</th>
                    <th style="width:30px;"></th>
                </tr> 
            </thead>    
            <tbody> 
                @foreach($storage as $key => $val)
                <tr>
                    <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
                    <td>{{ $val->coupon_name }}</td>
                    <td class="coupon">{{ $val->coupon_code }}</td>
                    <td class="coupon">{{ $val->date_start }}</td>
                    <td class="coupon">{{ $val->date_end }}</td>
                    <td class="coupon">{{ $val->coupon_quantity }}</td>

                    <td class="coupon">
                        <?php
                            if($val->coupon_condition == 1){
                        ?>
                            Giảm {{ $val->coupon_number }}%
                        <?php
                            }else{
                        ?>
                            Giảm {{ number_format($val->coupon_number,0,',','.') }}đ
                        <?php
                            }
                        ?>
                    </td>

                    <td class="coupon">
                        <?php
                            if($val->date_end >= $today){
                        ?>
                            <span style="color:green;">Còn hạn</span>
                        <?php
                            }else{
                        ?>
                            <span style="color:red;">Đã hết hạn</span>
                        <?php
                            }
                        ?>
                    </td>
                    <td>
                        <a href="{{URL::to('/restore-coupon/'.$val->coupon_id)}}" class="active " onclick="return confirm('Bạn có muốn khôi phục mã giảm giá này không?')">
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