<?php

namespace App\Http\Controllers;

use App\Exports\Ex_Pro;
use App\Imports\Im_Pro;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\Type;
use App\Models\Gallery;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Rating;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

session_start();

class ProductController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }

    public function all_product(){
        $this->AuthLogin();

        // Thông tin admin
        $info = Admin::where('admin_id', Session::get('admin_id'))->first();

        $all_product = DB::table('tbl_product') 
            ->join('tbl_category','tbl_category.category_id', '=', 'tbl_product.cat_id')
            ->join('tbl_brand','tbl_brand.brand_id', '=', 'tbl_product.brand_id')            
            ->join('tbl_type','tbl_type.type_id', '=', 'tbl_product.type_id')
            ->orderBy('tbl_product.product_id','desc')
            ->where('product_storage','0')->paginate(6);

        // $manager_product = view('admin.product.all_product')->with(compact('all_product'));
        return view('admin.product.all_product')->with(compact('info','all_product'));
    }

    public function import_pro(Request $request){
        $path = $request->file('file')->getRealPath();
        Excel::import(new Im_Pro, $path);
        return back();
    }

    public function export_pro(){
        return Excel::download(new Ex_Pro ,'product.xlsx');
    }

    public function add_product(){
        $this->AuthLogin();

        // Thông tin admin
        $info = Admin::where('admin_id', Session::get('admin_id'))->first();

        $cat_product = Category::orderBy('category_id','asc')->get();
        $brand_product = Brand::orderBy('brand_id','asc')->get();
        $type_product = Type::orderBy('type_id','asc')->get();

        return view('admin.product.add_product')
            ->with(compact('info', 'cat_product', 'brand_product', 'type_product'));
    }

    public function view_product($pro_slug){
        $this->AuthLogin();

        // Thông tin admin
        $info = Admin::where('admin_id', Session::get('admin_id'))->first();

        $detail = DB::table('tbl_product') 
            ->join('tbl_category','tbl_category.category_id', '=', 'tbl_product.cat_id')
            ->join('tbl_brand','tbl_brand.brand_id', '=', 'tbl_product.brand_id')            
            ->join('tbl_type','tbl_type.type_id', '=', 'tbl_product.type_id')
            ->where('tbl_product.product_slug', $pro_slug)->first();

        return view('admin.product.view_product')->with(compact('info', 'detail'));
    }

    public function save_product(Request $request){
        $this->AuthLogin();
        $this->validate($request, [
            'pro_name' => 'required|unique:tbl_product,product_name|max:255',
            'pro_slug' => 'required|max:255',
            'pro_qty' => 'required|integer',
            'pro_desc' => 'required',
            'pro_content' => 'required',
            'pro_price' => 'required|integer',
            'pro_image' => 'required|image',    //mimes:jpg,bmp,png      image (jpg, jpeg, png, bmp, gif, svg, or webp).
        ],
        [
            'pro_name.required' => 'Bạn chưa nhập tên sản phẩm',
            'pro_name.unique' => 'Tên sản phẩm đã có. Vui lòng điền tên khác',
            'pro_slug.required' => 'Bạn chưa nhập slug của sản phẩm',
            'pro_qty.required' => 'Bạn chưa nhập số lượng của sản phẩm',
            'pro_qty.integer' => 'Số lượng phải là một số nguyên',
            'pro_desc.required' => 'Bạn chưa nhập mô tả sản phẩm',
            'pro_content.required' => 'Bạn chưa nhập hướng dẫn bảo quản sản phẩm',
            'pro_price.required' => 'Bạn chưa nhập giá bán của sản phẩm',
            'pro_price.integer' => 'Giá sản phẩm phải là một số nguyên',
            'pro_image.required' => 'Bạn chưa chọn hình ảnh sản phẩm',
            'pro_image.image' => 'Tệp bạn chọn không phải là hình ảnh',
        ]);

        $data = array();
        $data['product_name'] = $request->pro_name;
        $data['product_slug'] = $request->pro_slug;
        $data['product_quantity'] = $request->pro_qty;
        $data['product_desc'] = $request->pro_desc;
        $data['product_content'] = $request->pro_content;
        $data['product_price'] = 0;
        $data['price_cost'] = $request->pro_price;
        $data['cat_id'] = $request->cat_pro;
        $data['brand_id'] = $request->brand_pro;
        $data['type_id'] = $request->type_pro;
        $data['product_status'] = $request->pro_status;
        $get_img = $request->file('pro_image');

        $path = "public/uploads/products/";
        $path_gallery = "public/uploads/gallery/";

        if($get_img){
            $new_img = $get_img->getClientOriginalName();
            $get_img->move($path, $new_img);
            File::copy($path.$new_img, $path_gallery.$new_img);
            $data['product_image'] = $new_img;
        }

        $pro_id = DB::table('tbl_product')->insertGetId($data);
        $gallery = new Gallery();
        $gallery->gallery_image = $new_img;
        $gallery->gallery_name = $request->pro_name;
        $gallery->product_id = $pro_id;
        $gallery->save();

        Toastr::success('Thêm sản phẩm thành công','');
        return Redirect::to('add-product');
    }

    public function unactive_product($pro_slug){
        $this->AuthLogin();
        Product::where('product_slug', $pro_slug)->update(['product_status'=>1]);
        Toastr::success('Kích hoạt sản phẩm thành công','');
        return redirect()->back();
    }

    public function active_product($pro_slug){
        $this->AuthLogin();
        Product::where('product_slug', $pro_slug)->update(['product_status'=>0]);
        Toastr::success('Bỏ kích hoạt sản phẩm thành công','');
        return redirect()->back();
    }

    public function edit_product($pro_slug){
        $this->AuthLogin();

        $cat_product = Category::orderBy('category_id','desc')->get();
        $brand_product = Brand::orderBy('brand_id','desc')->get();
        $type_product = Type::orderBy('type_id','desc')->get();

        $edit_product = Product::where('product_slug', $pro_slug)->get();
        // $manager_product = view('admin.product.edit_product')
        //     ->with(compact('cat_product','brand_product','type_product','edit_product'));

        return view('admin.product.edit_product')
            ->with(compact('cat_product','brand_product','type_product','edit_product'));
    }

    public function update_product(Request $request, $pro_slug){
        $this->AuthLogin();
        $data = array();
        $data['product_name'] = $request->pro_name;
        $data['product_slug'] = $request->pro_slug;
        $data['product_quantity'] = $request->pro_qty;
        $data['product_desc'] = $request->pro_desc;
        $data['product_content'] = $request->pro_content;
        $data['price_cost'] = $request->pro_price;
        $data['cat_id'] = $request->cat_pro;
        $data['brand_id'] = $request->brand_pro;
        $data['type_id'] = $request->type_pro;
        $get_img = $request->file('pro_image');

        if($get_img){
            $new_img = $get_img->getClientOriginalName();
            $get_img->move('public/uploads/products', $new_img);
            $data['product_image'] = $new_img;
            DB::table('tbl_product')->where('product_slug', $pro_slug)->update($data);
            Toastr::success('Cập nhật sản phẩm thành công','');
            return Redirect::to('all-product');
        }
        DB::table('tbl_product')->where('product_slug', $pro_slug)->update($data);
        Toastr::success('Cập nhật sản phẩm thành công','');
        return Redirect::to('all-product');
    }

    public function delete_product($pro_id){
        $this->AuthLogin();
        $del_pro = Product::find($pro_id);
        // unlink('public/uploads/products/'.$del_pro->product_image);
        // $del_pro->delete();
        $del_pro->product_storage = '1';
        $del_pro->save();
        Toastr::success('Xóa sản phẩm thành công','');
        return redirect()->back();
    }

    public function product_storage(){
        $this->AuthLogin();

        $storage = Product::where('product_storage','1')->orderBy('product_id','desc')->get();
        return view('admin.product.storage')->with(compact('storage'));
    }

    public function restore_product($product_id){
        $this->AuthLogin();
        $restore = Product::find($product_id);
        $restore->product_storage = '0';
        $restore->save();
        Toastr::success('Khôi phục sản phẩm thành công','');
        return redirect()->back();
    }


    // Comment
    public function all_comment(){
        $this->AuthLogin();

        // Thông tin admin
        $info = Admin::where('admin_id', Session::get('admin_id'))->first();

        $comment = Comment::with('product')->where('comment_parent_comment','=',0)->orderBy('comment_id','DESC')->get();
        $comment_rep = Comment::with('product')->where('comment_parent_comment','>',0)->get();
        return view('admin.comment.all_comment')->with(compact('info', 'comment', 'comment_rep'));
    }

    public function active_comment($cmt_id){
        $this->AuthLogin();
        Comment::where('comment_id', $cmt_id)->update(['comment_status'=>1]);
        Toastr::success('Bỏ duyệt bình luận','');
        return redirect()->back();
    }

    public function unactive_comment($cmt_id){
        $this->AuthLogin();
        Comment::where('comment_id', $cmt_id)->update(['comment_status'=>0]);
        Toastr::success('Đã duyệt bình luận','');
        return redirect()->back();
    }

    public function reply_comment(Request $request){
        $data = $request->all();
        $comment = new Comment();
        $comment->comment = $data['reply_comment'];
        $comment->comment_pro_id = $data['pro_id'];
        $comment->comment_parent_comment = $data['cmt_id'];
        $comment->comment_status = 0;
        $comment->comment_name = 'N&T Store';
        $comment->ord_detail_id = $data['ord_detail_id'];
        $comment->save();

        Toastr::success('Đã trả lời bình luận','');
        return redirect()->back();
    }

    //End Admin
    
    

    public function quickview(Request $request){
        $pro_id = $request->product_id;
        $product = Product::find($pro_id); 
        $gallery = Gallery::where('product_id', $pro_id)->get();
        $output['product_gallery'] = '';
        foreach($gallery as $key => $gal){
            $output['product_gallery'] .= '<p><img width="90%" src="public/uploads/gallery/'.$gal->gallery_image.'"></p>';
        }
        
        $output['product_name'] = $product->product_name;
        $output['product_id'] = $product->product_id;
        $output['product_desc'] = $product->product_desc;
        $output['product_content'] = $product->product_content;
        $output['product_price'] = number_format($product->price_cost,0,',','.').'VNĐ';
        $output['product_image'] = '<p><img width="90%" src="public/uploads/products/'.$product->product_image.'"></p>';
        echo json_encode($output);
    }

    public function details_product(Request $request, $pro_slug) {
        $cat_pro = Category::where('category_status','1')->orderBy('category_id','asc')->get();
        $brand_pro = Brand::where('brand_status','1')->orderBy('brand_id','desc')->get();
        $type_pro = Type::where('type_status','1')->orderBy('type_id','desc')->get();
        
        // Seo  
        $url_canonical = $request->url();

        // Thông tin khách hàng
        $result = Customer::where('customer_id', Session::get('customer_id'))->first(); 

        // tên sp trong banner
        $pro_name = Product::where('product_slug',$pro_slug)->limit(1)->get();

        // Chi tiết sản phẩm
        $details_pro = DB::table('tbl_product')
            ->join('tbl_category','tbl_category.category_id', '=', 'tbl_product.cat_id')
            ->join('tbl_brand','tbl_brand.brand_id', '=', 'tbl_product.brand_id')            
            ->join('tbl_type','tbl_type.type_id', '=', 'tbl_product.type_id')
            ->where('tbl_product.product_slug',$pro_slug)->get();

        foreach($details_pro as $key => $value){
            $product_id = $value->product_id;
        }

        $gallery = Gallery::where('product_id', $product_id)->get();

        $rating = OrderDetails::where('product_id', $product_id)->avg('rating');  //lấy trung bình số sao
        $rating = round($rating);   //làm tròn

        // Sản phẩm nổi bật theo số sao đánh giá
        $related_pro = DB::table('tbl_product')
            ->join('tbl_category','tbl_category.category_id', '=', 'tbl_product.cat_id')           
            ->join('tbl_brand','tbl_brand.brand_id', '=', 'tbl_product.brand_id')            
            ->join('tbl_type','tbl_type.type_id', '=', 'tbl_product.type_id')
            ->where('product_status','1')->orderBy('tbl_product.avg_star','desc')
            ->whereNotIn('tbl_product.product_slug', [$pro_slug])->limit(8)->get();

        return view('pages.product.show_details')
            ->with(compact('cat_pro','brand_pro','type_pro', 'url_canonical', 'result', 'pro_name', 'details_pro', 
            'gallery', 'rating', 'related_pro'));
    }

    public function load_comment(Request $request){
        $pro_id = $request->product_id;
        // $comment_name = Customer::find(Session::get('customer_id'));
        
        $comment = Comment::where('comment_pro_id', $pro_id)->where('comment_parent_comment','=',0)->where('comment_status',0)->get();
        $comment_rep = Comment::with('product')->where('comment_parent_comment','>',0)->get();
        $output = '';
        foreach($comment as $key => $cmt){
            $output.= ' 
                <div class="row style_comment">
                    <div class="col-md-2">
                        <img width="100%" src="'.url('/public/frontend/images/user.jpg').'" class="img img-responsive img-thumbnail">
                    </div>
                    <div class="col-md-10">
                        <p>
                            <span style="font-weight:bold; color:#000; margin-right:50px;">@'.$cmt->comment_name.'</span>
                            <span style="color:#000;">'.$cmt->comment_date.'</span>
                        </p>
                        <p>'.$cmt->comment.'</p>
                    </div>
                </div><p></p>
            ';

            foreach($comment_rep as $key => $rep_cmt)  {
                if($rep_cmt->comment_parent_comment == $cmt->comment_id)  {
                    $output.= ' 
                        <div class="row style_comment" style="margin:5px 0px 5px 80px; background: #f2f2f2;">

                            <div class="col-md-2">
                                <img width="80%" src="'.url('/public/backend/images/admin.jpg').'" class="img img-responsive img-thumbnail">
                            </div>
                            <div class="col-md-10">
                                <p>
                                    <span style="font-weight:bold; color:#000; margin-right:50px;">@'.$rep_cmt->comment_name.'</span>
                                    <span style="color:#000;">'.$rep_cmt->comment_date.'</span>
                                </p>
                                <p style="color:#000;">'.$rep_cmt->comment.'</p>
                                <p></p>
                            </div>
                        </div><p></p>';
                }
            }
        }
        echo $output;
    }

    public function send_comment(Request $request){
        $pro_id = $request->product_id;
        $cmt_name = $request->comment_name;
        $cmt_content = $request->comment_content;
        $det_id = $request->ord_detail_id;

        $cmt = new Comment();
        $cmt->comment_name = $cmt_name;
        $cmt->comment = $cmt_content;
        $cmt->comment_pro_id = $pro_id;
        $cmt->comment_status = 0;
        $cmt->comment_parent_comment = 0;
        $cmt->ord_detail_id = $det_id;
        $cmt->save();
    }
    
    public function insert_rating(Request $request){
        $data = $request->all();
        // $rating = new Rating();
        // $rating->product_id = $data['product_id'];
        // $rating->rating = $data['index'];
        // $rating->customer_id = Session::get('customer_id');
        // $rating->ord_detail_id = $data['ord_detail_id'];
        // $rating->save();

        $rating = OrderDetails::where('details_id',$data['ord_detail_id'])->first();
        $rating->rating = $data['index'];
        $rating->save();
        
        $product = Product::find($data['product_id']);
        $product->product_total_star = $product->product_total_star + $rating->rating;
        $product->product_total_review = $product->product_total_review + 1;
        $product->avg_star = $product->product_total_star/$product->product_total_review;
        $product->save();

        echo 'done';
    }

//     public function file_browser(Request $request){
//         $paths = glob(public_path('uploads/ckeditor/*'));

//         $fileNames = array();

//         foreach($paths as $path){
//             array_push($fileNames,basename($path));
//         }
//         $data = array(
//             'fileNames' => $fileNames
//         );
       
//         return view('admin.images.file_browser')->with($data);
//    }
}