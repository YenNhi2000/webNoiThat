<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Type;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

use App\Imports\Im_Cate;
use App\Exports\Ex_Cate;
use App\Models\Admin;
use App\Models\Customer;
use Maatwebsite\Excel\Facades\Excel;

session_start();

class CategoryProduct extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }

    public function all_category_product(){
        $this->AuthLogin();

        // Thông tin admin
        $info = Admin::where('admin_id', Session::get('admin_id'))->first();

        $all_cat = Category::where('category_storage','0')->orderBy('category_id','desc')->get();
        return view('admin.category.all_category')->with(compact('info','all_cat'));
    }

    public function import_cate(Request $request){
        $path = $request->file('file')->getRealPath();
        Excel::import(new Im_Cate, $path);
        return back();
    }

    public function export_cate(){
        return Excel::download(new Ex_Cate ,'category_product.xlsx');
    }

    public function add_category_product(){
        $this->AuthLogin();

        // Thông tin admin
        $info = Admin::where('admin_id', Session::get('admin_id'))->first();

        return view('admin.category.add_category')->with(compact('info'));
    }

    public function save_category_product(Request $request){
        $this->AuthLogin();

        $this->validate($request, [
            'cat_name' => 'required|unique:tbl_category,category_name|max:255',
            'cat_slug' => 'required|max:255',
            'cat_desc' => 'required',
        ],
        [
            'cat_name.required' => 'Bạn chưa nhập tên danh mục sản phẩm',
            'cat_name.unique' => 'Tên danh mục đã có. Vui lòng điền tên khác',
            'cat_slug.required' => 'Bạn chưa nhập slug danh mục sản phẩm',
            'cat_desc.required' => 'Bạn chưa nhập mô tả danh mục sản phẩm',
        ]);

        $data = $request->all();
        $category = new Category();
        $category->category_name = $data['cat_name'];
        $category->category_slug = $data['cat_slug'];
        $category->category_desc = $data['cat_desc'];
        $category->category_status = $data['cat_status'];
        $category->save();

        Toastr::success('Thêm danh mục sản phẩm thành công','');
        return Redirect::to('add-category-product');
    }

    public function unactive_category($cat_slug){
        $this->AuthLogin();
        Category::where('category_slug', $cat_slug)->update(['category_status'=>1]);
        Toastr::success('Kích hoạt danh mục sản phẩm thành công','');
        return redirect()->back();
    }

    public function active_category($cat_slug){
        $this->AuthLogin();
        Category::where('category_slug', $cat_slug)->update(['category_status'=>0]);
        Toastr::success('Bỏ kích hoạt danh mục sản phẩm thành công','');
        return redirect()->back();
    }

    public function edit_category_product($cat_slug){
        $this->AuthLogin();

        // Thông tin admin
        $info = Admin::where('admin_id', Session::get('admin_id'))->first();

        $edit_category_product = Category::where('category_slug', $cat_slug)->get();
        return view('admin.category.edit_category')->with(compact('info','edit_category_product'));
    }

    public function update_category_product(Request $request, $cat_id){
        $this->AuthLogin();
        
        $data = $request->all();
        $category = Category::find($cat_id);
        $category->category_name = $data['cat_name'];
        $category->category_slug = $data['cat_slug'];
        $category->category_desc = $data['cat_desc'];
        $category->save();

        Toastr::success('Cập nhật danh mục sản phẩm thành công','');
        return Redirect::to('all-category-product');
    }

    public function delete_category_product($cat_id){
        $this->AuthLogin();
        $del_cat = Category::find($cat_id);
        $del_cat->category_storage = '1';
        $del_cat->save();
        Toastr::success('Xóa danh mục sản phẩm thành công','');
        return redirect()->back();
    }

    public function category_storage(){
        $this->AuthLogin();

        // Thông tin admin
        $info = Admin::where('admin_id', Session::get('admin_id'))->first();

        $storage = Category::where('category_storage','1')->orderBy('category_id','desc')->get();
        return view('admin.category.storage')->with(compact('info','storage'));
    }

    public function restore_category($category_id){
        $this->AuthLogin();
        $restore = Category::find($category_id);
        $restore->category_storage = '0';
        $restore->save();
        Toastr::success('Khôi phục danh mục sản phẩm thành công','');
        return redirect()->back();
    }

    //End Admin

    
    public function show_category(Request $request, $cate_slug){
        $cat_pro = Category::where('category_status','1')->orderBy('category_id','asc')->get();
        $brand_pro = Brand::where('brand_status','1')->orderBy('brand_id','asc')->get();
        $type_pro = Type::where('type_status','1')->orderBy('type_id','asc')->get();
        $feature_pro = Product::where('product_status','1')->orderBy('avg_star','desc')->limit(6)->get();

        // Seo  
        $url_canonical = $request->url();

        // Thông tin khách hàng
        $result = Customer::where('customer_id', Session::get('customer_id'))->first(); 

        $cat_name = DB::table('tbl_category')
            ->join('tbl_product','tbl_product.cat_id', '=', 'tbl_category.category_id')
            ->join('tbl_brand','tbl_brand.brand_id', '=', 'tbl_product.brand_id')
            ->join('tbl_type','tbl_type.type_id', '=', 'tbl_product.type_id')
            ->where('category_slug', $cate_slug)->first();

        if(isset($_GET['brand'])){
            $brand_id = $_GET['brand'];
            $brand_arr = explode(",", $brand_id);

            $cat_by_id = DB::table('tbl_product')
                ->join('tbl_category','tbl_category.category_id', '=', 'tbl_product.cat_id')
                ->join('tbl_brand','tbl_brand.brand_id', '=', 'tbl_product.brand_id')
                ->join('tbl_type','tbl_type.type_id', '=', 'tbl_product.type_id')
                ->where('tbl_category.category_slug', $cate_slug)->where('tbl_category.category_status','1')
                ->whereIn('tbl_product.brand_id', $brand_arr)->paginate(6);
        }else{
            $cat_by_id = DB::table('tbl_product')
                ->join('tbl_category','tbl_category.category_id', '=', 'tbl_product.cat_id')
                ->join('tbl_brand','tbl_brand.brand_id', '=', 'tbl_product.brand_id')
                ->join('tbl_type','tbl_type.type_id', '=', 'tbl_product.type_id')
                ->where('tbl_category.category_slug', $cate_slug)->where('tbl_category.category_status','1')
                ->where('tbl_product.brand_id', $cat_name->brand_id)->paginate(6);
        }

        if(isset($_GET['type'])){
            $type_id = $_GET['type'];
            $type_arr = explode(",", $type_id);

            $cat_by_id = DB::table('tbl_product')
                ->join('tbl_category','tbl_category.category_id', '=', 'tbl_product.cat_id')
                ->join('tbl_brand','tbl_brand.brand_id', '=', 'tbl_product.brand_id')
                ->join('tbl_type','tbl_type.type_id', '=', 'tbl_product.type_id')
                ->where('tbl_category.category_slug', $cate_slug)->where('tbl_category.category_status','1')
                ->whereIn('tbl_product.type_id', $type_arr)->paginate(6);
        }else{
            $cat_by_id = DB::table('tbl_product')
                ->join('tbl_category','tbl_category.category_id', '=', 'tbl_product.cat_id')
                ->join('tbl_brand','tbl_brand.brand_id', '=', 'tbl_product.brand_id')
                ->join('tbl_type','tbl_type.type_id', '=', 'tbl_product.type_id')
                ->where('tbl_category.category_slug', $cate_slug)->where('tbl_category.category_status','1')
                ->where('tbl_product.type_id', $cat_name->type_id)->paginate(6);
        }

        // $cat_name = Category::where('category_slug', $cate_slug)->get();

        // foreach($cat_name as $key => $cate){
        //     $category_id = $cate->category_id;
        // }

        // if(isset($_GET['sort_by'])){

        //     $sort_by = $_GET['sort_by'];

        //     if($sort_by=='giam_dan'){

        //         $cat_by_id = Product::with('category')->where('cat_id',$category_id)->orderBy('price_cost','DESC')->paginate(6)->appends(request()->query());

        //     }elseif($sort_by=='tang_dan'){

        //         $cat_by_id = Product::with('category')->where('cat_id',$category_id)->orderBy('price_cost','ASC')->paginate(6)->appends(request()->query());

        //     }elseif($sort_by=='kytu_za'){

        //         $cat_by_id = Product::with('category')->where('cat_id',$category_id)->orderBy('product_name','DESC')->paginate(6)->appends(request()->query());

        //     }elseif($sort_by=='kytu_az'){

        //         $cat_by_id = Product::with('category')->where('cat_id',$category_id)->orderBy('product_name','ASC')->paginate(6)->appends(request()->query());
            
        //     }
        // }else{

        //     $cat_by_id = Product::with('category')->where('cat_id',$category_id)->orderBy('product_id','DESC')->paginate(6);
        
        // }

        //get price
        // $min_price = Product::min('product_price');
        // $max_price = Product::max('product_price');

        return view('pages.category.show_cat')
            ->with(compact('cat_pro','brand_pro','type_pro','feature_pro', 'cat_by_id', 'cat_name', 'url_canonical', 'result'));
            // 'min_price', 'max_price'));
    }
}
