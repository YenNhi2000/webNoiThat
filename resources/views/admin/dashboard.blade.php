@extends('admin_layout')
@section('admin_content')

<div class="tables">
    <div class="row"> 
        <h5 class="text-center text-uppercase">Thống kê doanh số</h5>

        <form autocomplete="off">
            @csrf

            <div class="col-md-2">
                <p>Từ ngày: <input type="text" id="datepicker1" class="form-control"></p>
                <input type="button" id="btn-filter" class="lockq" value="Lọc kết quả"></p>
            </div>

            <div class="col-md-2">
                <p>Đến ngày: <input type="text" id="datepicker2" class="form-control"></p>
            </div>

            <!-- <div class="col-md-2">
                <p>
                    Lọc theo: 
                    <select class="dashboard-filter form-control" >
                        <option>--Chọn--</option>
                        
                        <option value="7ngay">7 ngày qua</option>
                        <option value="thangtruoc">tháng trước</option>
                        <option value="thangnay">tháng này</option>
                        <option value="365ngayqua">365 ngày qua</option>
                    </select>
                </p>
            </div> -->
        </form>

        <div class="col-md-12">
			<div id="chart" style="height: 250px;"></div>
		</div>
    </div>
</div>

@endsection