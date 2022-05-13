@extends('admin_layout')
@section('admin_content')

<div class="tables">
    <!-- <h2 class="title1">Danh sách thương hiệu sản phẩm</h2> -->
    <div class="bs-example widget-shadow" data-example-id="hoverable-table"> 
        <h4 class="text-center text-uppercase">Danh sách bình luận</h4>
        
        <table class="table table-striped b-t b-light table-hover">
            <thead>
                <tr>
                    <th>Tên người gửi</th>
                    <th style="width:450px;">Bình luận</th>
                    <th>Ngày gửi</th>
                    <th>Sản phẩm</th>
                    <th>Duyệt</th>
                    <!-- <th style="width:70px;"></th> -->
                </tr>
            </thead>
            <tbody>
                @foreach($comment as $key => $cmt)
                <tr>
                    <td>{{ $cmt->comment_name }}</td>
                    <td>{{ $cmt->comment }}
                        <ul class="list_rep" >
                            Trả lời : 

                            @foreach($comment_rep as $key => $cmt_reply)
                                @if($cmt_reply->comment_parent_comment == $cmt->comment_id)
                                    <li style="list-style-type: decimal; color: blue; margin: 5px 40px;"> 
                                        {{$cmt_reply->comment}}
                                    </li>
                                @endif
                            @endforeach

                        </ul>
                        <form method="post" action="{{url('/reply-comment')}}">
                            @csrf
                        
                            @if($cmt->comment_status == 0)
                                <input type="hidden" value="{{$cmt->ord_detail_id}}" name="ord_detail_id"/>
                                <input type="hidden" value="{{$cmt->comment_pro_id}}" name="pro_id"/>
                                <input type="hidden" value="{{$cmt->comment_id}}" name="cmt_id"/>
                                <br/><textarea class="form-control" name="reply_comment" rows="5"></textarea>
                                <br/><button type="submit" class="btn btn-default btn-xs reply">
                                        Trả lời bình luận
                                    </button>
                            @endif
                        </form>
                    </td>
                    <td>{{ $cmt->comment_date }}</td>
                    <td>
                        <a href="{{url('/chi-tiet-san-pham/'.$cmt->product->product_slug)}}" target="_blank">
                            {{ $cmt->product->product_name }}
                        </a>
                    </td>
                    <td>
                        @if($cmt->comment_status == 1)
                            <a href="{{URL::to('/unactive-comment/'.$cmt->comment_id)}}" class="btn btn-primary btn-xs">
                                Duyệt
                            </a>
                        @else 
                            <a href="{{URL::to('/active-comment/'.$cmt->comment_id)}}" class="btn btn-danger btn-xs">
                                Bỏ duyệt
                            </a>
                        @endif
                    </td>
                    <!-- <td>
                        <a href="{{URL::to('/edit-comment-product/'.$cmt->comment_slug)}}" class="active edit" ui-toggle-class="">
                            <i class="fa fa-pencil-square-o text-success text-active" title="Chỉnh sửa"></i>
                        </a>
                        <a href="{{URL::to('/delete-comment-product/'.$cmt->comment_id)}}" class="active delete" onclick="return confirm('Bạn có muốn xóa loại sản phẩm này không?')" ui-toggle-class="">
                            <i class="fa fa-times text-danger text" title="Xóa"></i>
                        </a>

                    </td> -->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection