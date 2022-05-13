@extends('admin_layout')
@section('admin_content')

<div class="tables">
    <!-- <h2 class="title1">Danh sách thương hiệu sản phẩm</h2> -->
    <div class="bs-example widget-shadow" data-example-id="hoverable-table"> 
        <h4 class="text-center text-uppercase">Danh sách loại sản phẩm</h4>
        
        <div class="row w3-res-tb">
            <div class="col-sm-5 m-b-xs"> 
                <a href="{{url('/add-type-product')}}" class="add">Thêm mới</a>
                <a href="{{url('/type-storage')}}" class="storage">Thùng rác</a>
            </div>
            <div class="col-sm-7"></div>
        </div>
        
        <table class="table table-striped b-t b-light table-hover">
            <thead>
                <tr>
                    <th style="width:20px;">
                        <label class="i-checks m-b-none">
                            <input type="checkbox"><i></i>
                        </label>
                    </th>
                    <th>Tên loại</th>
                    <th>Slug</th>
                    <th>Trạng thái</th>
                    <th style="width:70px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($all_type_product as $key => $type_pro)
                <tr>
                    <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
                    <td>{{ $type_pro->type_name }}</td>
                    <td>{{ $type_pro->type_slug }}</td>
                    <td>
                        <span class="text-ellipsis">
                            <?php
                                if($type_pro->type_status == 0){
                            ?>
                                    <a href="{{URL::to('/unactive-type/'.$type_pro->type_slug)}}"><span class="fa fa-square-o"></span></a>
                            <?php
                                }else{
                            ?>
                                    <a href="{{URL::to('/active-type/'.$type_pro->type_slug)}}"><span class="fa fa-check-square-o"></span></a>
                            <?php
                                }
                            ?>
                        </span>
                    </td>
                    <td>
                        <a href="{{URL::to('/edit-type-product/'.$type_pro->type_slug)}}" class="active edit" ui-toggle-class="">
                            <i class="fa fa-pencil-square-o text-success text-active" title="Chỉnh sửa"></i>
                        </a>
                        <a href="{{URL::to('/delete-type-product/'.$type_pro->type_id)}}" class="active delete" onclick="return confirm('Bạn có muốn xóa loại sản phẩm này không?')" ui-toggle-class="">
                            <i class="fa fa-times text-danger text" title="Xóa"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

<!-- import data -->
        <div class="row">
            <form action="{{url('import-type')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-sm-3">
                    <input type="file" name="file" accept=".xlsx"><br>
                </div>
                <div class="col-sm-1">
                    <input type="submit" value="Import" name="import_csv" title="Import file Excel" class="btn btn-warning">
                </div>
            </form>

<!-- export data -->
            <form action="{{url('export-type')}}" method="POST">
                @csrf
                <div class="col-sm-1">
                    <input type="submit" value="Export" name="export_csv" title="Export file Excel" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
</div>

@endsection