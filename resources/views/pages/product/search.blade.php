@extends('layout')
@section('content')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- banner -->
<div class="banner_inner">
    <div class="services-breadcrumb">
        <div class="inner_breadcrumb">
            <ul class="short">
                <li>
                    <a href="{{URL::to('/')}}">Trang chủ</a>
                    <i>|</i>
                </li>
                <li>Tìm kiếm</li>
            </ul>
        </div>
    </div>

</div>
<!--//banner -->



<section class="banner-bottom-wthreelayouts py-lg-5 py-3">
    <div class="container-fluid">
        <div class="inner-sec-shop px-lg-4 px-3">
            <h3 class="tittle-w3layouts my-lg-4 my-4">Kết quả tìm kiếm</h3>
            <h5>
                <?php
                    use Illuminate\Support\Facades\Session;

                    $search = Session::get('search');
                    if ($search){
                        echo '<span>'.$search.'</span>';
                        Session::put('search', null);
                    }
                ?>
            </h5>
            
            <p>
                <?php
                    $message = Session::get('message');
                    if ($message){
                        echo '<span>'.$message.'</span>';
                        Session::put('message', null);
                    }
                ?>
            </p>

            <div class="row mt-lg-3 mt-0">
                @foreach($search_pro as $key => $pro)
                <div class="col-md-3 product-men women_two">
                    <div class="product-googles-info googles">
                        <form>

                            @csrf

                            <div class="men-pro-item">
                                <div class="men-thumb-item">
                                    <img src="{{URL::to('public/uploads/products/'.$pro->product_image)}}" class="img-fluid" alt="">
                                    <div class="men-cart-pro">
                                        <div class="inner-men-cart-pro">
                                            <input type="button" class="link-product-add-cart xemnhanh" value="Xem nhanh"
                                                data-toggle="modal" data-target="#xemnhanh" data-id_pro="{{ $pro->product_id }}"/>
                                        </div>
                                    </div>
                                    <!-- <span class="product-new-top">New</span> -->
                                </div>

                                <div id="xemnhanh" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-lg">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <!-- <div class="modal-header">
                                            </div> -->
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <span id="pro_quickview_image"></span>
                                                        <span id="pro_quickview_gallery"></span>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                        <h5 id="pro_quickview_title"></h5>
                                                        <small>Mã sản phẩm: <span id="pro_quickview_id"></span></small>
                                                        <h4>
                                                            <span id="pro_quickview_price"></span>
                                                        </h4><hr/>

                                                        <label>Số lượng:</label>
                                                        <input type="number" name="qty" min="1" class="cart_product_qty" value="1" />
                                                        <!-- <input type="hidden" name="proid_hidden" value="" /> -->

                                                        <h5 class="detail">Mô tả sản phẩm:</h5>
                                                        <span id="pro_quickview_desc"></span>
                                                        <h5 class="detail">Hướng dẫn bảo quản:</h5>
                                                        <span id="pro_quickview_content"></span>
                                                        <input type="button" value="Mua ngay" class="btn-quickview" data-id_pro="">
                                                        <!-- <p>hoặc <a href="{{URL::to('/chi-tiet-san-pham/'.$pro->product_slug)}}">Xem chi tiết</a></p> -->
                                                    </div>
                                                </div>                        
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="item-info-product">

                                    <div class="info-product-price">
                                        <div class="grid_meta">
                                            <div class="product_price">
                                                <h4>
                                                    <a href="{{URL::to('/chi-tiet-san-pham/'.$pro->product_slug)}}">{{$pro->product_name}}</a>
                                                </h4>
                                                <div class="grid-price mt-2">
                                                    @if($pro->product_price == 0)
                                                        <span class="money">{{number_format($pro->price_cost).' '.'VND'}}</span>
                                                        <input type="hidden" value="{{ $pro->price_cost }}" class="cart_product_price_{{$pro->product_id}}">
                                                    @elseif($pro->product_price <= $pro->price_cost)
                                                        <span class="money">{{number_format($pro->product_price).' '.'VND'}}</span>
                                                        <input type="hidden" value="{{ $pro->product_price }}" class="cart_product_price_{{$pro->product_id}}">
                                                    @endif
                                                </div>
                                            </div>

                                            <ul class="list-inline" title="Average Rating">

                                                @php
                                                    $ratingg = round($pro->avg_star);
                                                @endphp

                                                @for($i = 1; $i <= 5; $i++)
                                                    @php
                                                        if($i <= $ratingg){
                                                            $color = 'color:#ffcc00;';
                                                        }
                                                        else {
                                                            $color = 'color:#ccc;';
                                                        }
                                                    @endphp
                                                
                                                    <li title="star_rating" style="{{$color}};" class="rating"> &#9733; </li>
                                                @endfor
                                            </ul>
                                        </div>
                                        <div class="googles single-item hvr-outline-out">
                                            <input type="hidden" value="{{ $pro->product_id }}" class="cart_product_id_{{$pro->product_id}}">
                                            <input type="hidden" value="{{ $pro->product_name }}" class="cart_product_name_{{$pro->product_id}}">
                                            <input type="hidden" value="{{ $pro->product_image }}" class="cart_product_image_{{$pro->product_id}}">
                                            <input type="hidden" value="1" class="cart_product_qty_{{$pro->product_id}}">
                                            <button type="button" class="googles-cart pgoogles-cart" data-id_product="{{$pro->product_id}}">
                                                <i class="fa fa-cart-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@endsection