@extends('admin_layout')
@section('admin_content')

<div class="tables">
    <div class="bs-example widget-shadow" data-example-id="hoverable-table"> 
        <h4 class="text-center text-uppercase">Danh sách danh mục sản phẩm</h4>
        <div class="row w3-res-tb">
            <div class="col-sm-5 m-b-xs">
                <a href="{{url('/add-category-product')}}" class="add">Thêm mới</a>
                <a href="{{url('/category-storage')}}" class="storage">Thùng rác</a>
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
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th>Trạng thái</th>
                    <th style="width:70px;"></th>
                </tr> 
            </thead>    
            <tbody> 
                @foreach($all_cat as $key => $cat_pro)
                <tr>
                    <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
                    <td>{{ $cat_pro->category_name }}</td>
                    <td>{{ $cat_pro->category_slug }}</td>
                    <td>
                        <span class="text-ellipsis">
                            <?php
                                if($cat_pro->category_status == 0){
                            ?>
                                    <a href="{{URL::to('/unactive-category/'.$cat_pro->category_slug)}}"><span class="fa fa-square-o"></span></a>
                            <?php
                                }else{
                            ?>
                                    <a href="{{URL::to('/active-category/'.$cat_pro->category_slug)}}"><span class="fa fa-check-square-o"></span></a>
                            <?php
                                }
                            ?>
                        </span>
                    </td>
                    <td>
                        <a href="{{URL::to('/edit-category-product/'.$cat_pro->category_slug)}}" class="active edit" ui-toggle-class="">
                            <i class="fa fa-pencil-square-o text-success text-active" title="Chỉnh sửa"></i>
                        </a>
                        <a href="{{URL::to('/delete-category-product/'.$cat_pro->category_id)}}" class="active delete" onclick="return confirm('Bạn có muốn xóa danh mục này không?')" ui-toggle-class="">
                            <i class="fa fa-times text-danger text" title="Xóa"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody> 
        </table>
        
<!-- import data -->
        <div class="row">
            <form action="{{url('import-cate')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-sm-3">
                    <input type="file" name="file" accept=".xlsx"><br>
                </div>
                <div class="col-sm-1">
                    <input type="submit" value="Import" name="import_csv" title="Import file Excel" class="btn btn-warning">
                </div>
            </form>

<!-- export data -->
            <form action="{{url('export-cate')}}" method="POST">
                @csrf
                <div class="col-sm-1">
                    <input type="submit" value="Export" name="export_csv" title="Export file Excel" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
</div>

@endsection     