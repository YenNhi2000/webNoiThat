@extends('admin_layout')
@section('admin_content')

<div class="tables">
    <div class="bs-example widget-shadow" data-example-id="hoverable-table">
        <div class="w3-res-tb mb-2 luutru">
            <a href="{{url('/all-category-product')}}" class="">
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
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th>Trạng thái</th>
                    <th style="width:60px;"></th>
                </tr> 
            </thead>    
            <tbody> 
                @foreach($storage as $key => $val)
                <tr>
                    <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
                    <td>{{ $val->category_name }}</td>
                    <td>{{ $val->category_slug }}</td>
                    <td>
                        <span class="text-ellipsis">
                            <?php
                                if($val->category_status == 0){
                            ?>
                                    <a href="{{URL::to('/unactive-category/'.$val->category_slug)}}"><span class="fa fa-square-o"></span></a>
                            <?php
                                }else{
                            ?>
                                    <a href="{{URL::to('/active-category/'.$val->category_slug)}}"><span class="fa fa-check-square-o"></span></a>
                            <?php
                                }
                            ?>
                        </span>
                    </td>
                    <td>
                        <a href="{{URL::to('/restore-category/'.$val->category_id)}}" class="active" onclick="return confirm('Bạn có muốn khôi phục danh mục này không?')">
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