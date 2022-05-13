<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Type;
use Illuminate\Support\Facades\Redirect;
session_start();

class HomeController extends Controller
{
    public function index(Request $request){
        $cat_pro = Category::where('category_status','1')->orderBy('category_id','asc')->get();
        $brand_pro = Brand::where('brand_status','1')->orderBy('brand_id','asc')->get();
        $type_pro = Type::where('type_status','1')->orderBy('type_id','asc')->get();

        // Seo  
        $url_canonical = $request->url();

        // Thông tin khách hàng
        $result = Customer::where('customer_id', Session::get('customer_id'))->first(); 

        // Sản phẩm mới
        $new_product = Product::where('product_status','1')->orderBy('product_id','desc')->limit(8)->get();

        $feature_pro = Product::where('product_status','1')->orderBy('avg_star','desc')->limit(8)->get();
        
        return view('pages.home')
            ->with(compact('cat_pro', 'brand_pro', 'type_pro', 'url_canonical', 'result', 'new_product', 'feature_pro'));
    }

    public function search(Request $request){
        $cat_pro = Category::where('category_status','1')->orderBy('category_id','desc')->get();
        $brand_pro = Brand::where('brand_status','1')->orderBy('brand_id','desc')->get();
        $type_pro = Type::where('type_status','1')->orderBy('type_id','desc')->get();

        // Seo  
        $url_canonical = $request->url();

        // Thông tin khách hàng
        $result = Customer::where('customer_id', Session::get('customer_id'))->first(); 

        $key = $request->keywords;
        $search_pro = Product::where('product_name', 'like', '%'.$key.'%')->get();

        Session::put('search', "Từ khóa: ".$key);

        if ($search_pro){
            return view('pages.product.search')
                ->with(compact('cat_pro', 'brand_pro', 'type_pro', 'search_pro', 'url_canonical', 'result'));
        }else{
            Session::put('message', "Không tìm thấy sản phẩm");
            return view('pages.product.search');
        }
    }
}
