<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
session_start();

class CustomerController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }

    public function all_customer(){
        $this->AuthLogin();

        // Thông tin admin
        $info = Admin::where('admin_id', Session::get('admin_id'))->first();

        $all_customer = Customer::where('customer_storage','0')->orderBy('customer_id','desc')->get();
        return view('admin.customer.all_customer')->with(compact('info','all_customer'));
    } 

    public function search(Request $request){
        // $key = $request->keywords;
        // $search_pro = Customer::where('product_name', 'like', '%'.$key.'%')->get();

        // Session::put('search', "Từ khóa: ".$key);

        // if ($search_pro){
        //     return view('pages.product.search')->with(compact('cat_pro', 'brand_pro', 'type_pro', 'search_pro'));
        // }else{
        //     Session::put('message', "Không tìm thấy sản phẩm");
        //     return view('pages.product.search');
        // }


        //     if($request->get('query'))
        //     {
        //         $query = $request->get('query');
        //         $data = Customer::where('customer_name', 'LIKE', "%{$query}%")->get();
        //         $output = '';
        //         foreach($data as $row)
        //         {
        //            $output .= '<tr>
        //                         <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
        //                         <td>'.$row->customer_name.'</td>
        //                         <td>'.$row->customer_phone.'</td>
        //                         <td>'.$row->customer_email.'</td>
        //                         <td>'.$row->customer_email.'</td>
        //                     </tr>';
        //        }
        //        $output .= '</ul>';
        //        echo $output;
        //    }
    }

    public function delete_customer($customer_id){
        $this->AuthLogin();
        $del_cus = Customer::find($customer_id);
        $del_cus->customer_storage = '1';
        $del_cus->save();
        Toastr::success('Xóa khách hàng thành công','');
        return redirect()->back();
    }

    public function customer_storage(){
        $this->AuthLogin();

        // Thông tin admin
        $info = Admin::where('admin_id', Session::get('admin_id'))->first();

        $storage = Customer::where('customer_storage','1')->orderBy('customer_id','desc')->get();
        return view('admin.customer.storage')->with(compact('info','storage'));
    }

    public function restore_customer($customer_id){
        $this->AuthLogin();
        $restore = Customer::find($customer_id);
        $restore->customer_storage = '0';
        $restore->save();
        Toastr::success('Khôi phục khách hàng thành công','');
        return redirect()->back();
    }
// End Admin


    public function AuthLoginCus(){
        $cus_id = Session::get('customer_id');
        if($cus_id){
            return Redirect::to('/');
        }else{
            return Redirect::to('/dang-nhap')->send();
        }
    }

    public function autocomplete_ajax(Request $request){
        $data = $request->all();

        if ($data['query']){
            $product = Product::where('product_status', 1)->where('product_name', 'LIKE', '%'.$data['query'].'%')->orderBy('avg_star','desc')->get();   //->orWhere()
            $output = '<ul class="dropdown-menu" style="display:block;">';
            foreach($product as $key => $val){
                $output .='
                    <li class="li-search"><a href="#">'.$val->product_name.'</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function register(Request $request){
        $cat_pro = Category::where('category_status','1')->orderBy('category_id','asc')->get();
        $brand_pro = Brand::where('brand_status','1')->orderBy('brand_id','desc')->get();
        $type_pro = Type::where('type_status','1')->orderBy('type_id','desc')->get();

        // Seo  
        $url_canonical = $request->url();

        return view('pages.account.register')->with(compact('cat_pro', 'brand_pro', 'type_pro', 'url_canonical'));
    }
    
    public function add_customer(Request $request){  
        $this->validate($request, [
            'fullname' => 'required|max:255',
            'phone' => 'required|digits:10',
            'email' => 'required|email:rfc,dns|unique:tbl_customers,customer_email|max:100',
            'pass' => 'required|min:5'
        ],
        [
            'fullname.required' => 'Bạn chưa nhập họ và tên',
            'phone.required' => 'Bạn chưa nhập số điện thoại',
            'phone.digits' => 'Số điện thoại chỉ chứa ký tự số có độ dài là 10 ký tự',
            'email.required' => 'Bạn chưa nhập email',
            'email.email' => 'Email bạn nhập chưa đúng',
            'email.unique' => 'Email bạn nhập đã tồn tại. Vui lòng nhập email khác',
            'pass.required' => 'Bạn chưa nhập mật khẩu',
            'pass.min' => 'Mật khẩu của bạn quá yếu',
        ]);

        $data = $request->all();
        $customer = new Customer();
        $customer->customer_name = $data['fullname'];
        $customer->customer_phone = $data['phone'];
        $customer->customer_email = $data['email'];        
        $customer->customer_password = md5($data['pass']);
        $customer->save();
        $customer_id = $customer->customer_id;
        $customer_name = $customer->customer_name;

        Session::put('customer_id', $customer_id);
        Session::put('customer_name', $customer_name);
        return Redirect::to('/');
    }

    public function login(Request $request){
        $cat_pro = Category::where('category_status','1')->orderBy('category_id','asc')->get();
        $brand_pro = Brand::where('brand_status','1')->orderBy('brand_id','desc')->get();
        $type_pro = Type::where('type_status','1')->orderBy('type_id','desc')->get();

        // Seo  
        $url_canonical = $request->url();

        $customer_id = Session::get('customer_id');
        $shipping = DB::table('tbl_shipping')
            ->join('tbl_customers', 'tbl_shipping.customer_id', '=', 'tbl_customers.customer_id')
            ->where('tbl_customers.customer_id', $customer_id)->get();

        return view('pages.account.login')->with(compact('cat_pro', 'brand_pro', 'type_pro', 'url_canonical'));
    }

    public function login_customer(Request $request){
        $this->validate($request, [
            'customer_email' => 'required|email:rfc,dns|max:100',
            'customer_pass' => 'required'
        ],
        [
            'customer_email.required' => 'Bạn chưa nhập email',
            'customer_email.email' => 'Email bạn nhập chưa đúng',
            'customer_pass.required' => 'Bạn chưa nhập mật khẩu',
        ]);

        $data = $request->all();
        $email = $data['customer_email'];
        $pass = md5($data['customer_pass']);

        $result = Customer::where('customer_email', $email)->where('customer_password', $pass)->first(); 

        if ($result){
            Session::put('customer_id', $result->customer_id);
            Session::put('customer_name', $result->customer_name);
            
            $cart = Session::get('cart');
            if ($cart){
                return Redirect::to('/thanh-toan');
            }
            else{
                return Redirect::to('/');
            }
        }else{
            Session::put('errorLogin', "Email hoặc mật khẩu không đúng. Vui lòng nhập lại");
            return Redirect::to('/dang-nhap');
        }
    }

    public function change_pass(Request $request){
        $this->AuthLoginCus();

        $cat_pro = Category::where('category_status','1')->orderBy('category_id','asc')->get();
        $brand_pro = Brand::where('brand_status','1')->orderBy('brand_id','desc')->get();
        $type_pro = Type::where('type_status','1')->orderBy('type_id','desc')->get();

        // Seo  
        $url_canonical = $request->url();

        // Thông tin khách hàng
        $result = Customer::where('customer_id', Session::get('customer_id'))->first(); 

        return view('pages.account.change_pass')->with(compact('cat_pro', 'brand_pro', 'type_pro', 'url_canonical', 'result'));
    }

    public function confirm_pass(Request $request){
        $this->AuthLoginCus();

        $this->validate($request, [
            'old_pass' => 'required',
            'new_pass' => 'required|min:5',
            're_new_pass' => 'required|same:new_pass',
        ],
        [
            'old_pass.required' => 'Bạn chưa nhập mật khẩu hiện tại',
            'new_pass.required' => 'Bạn chưa nhập mật khẩu mới',
            'new_pass.min' => 'Mật khẩu mới của bạn quá yếu',
            're_new_pass.required' => 'Bạn chưa xác nhận mật khẩu mới',
            're_new_pass.same' => 'Mật khẩu nhập lại của bạn không trùng khớp',
        ]);

        $data = $request->all();
        $cus_id = Session::get('customer_id');

        $reset = Customer::find($cus_id);
        if (md5($data['old_pass']) == $reset->customer_password){
            if (md5($data['new_pass']) != $reset->customer_password){
                $reset->customer_password = md5($data['new_pass']);
                $reset->save();
                Toastr::success('Đổi mật khẩu thành công','');

                return redirect('doi-mat-khau');
            }else{
                Toastr::error('Bạn vừa nhập mật khẩu hiện tại. Vui lòng nhập mật khẩu khác','');
                return redirect()->back();
            }
        }else{
            Toastr::error('Mật khẩu sai. Vui lòng nhập lại mật khẩu hiện tại','');
            return redirect()->back();
        }
    }

    public function forgot_pass(Request $request){
        $cat_pro = Category::where('category_status','1')->orderBy('category_id','desc')->get();
        $brand_pro = Brand::where('brand_status','1')->orderBy('brand_id','desc')->get();
        $type_pro = Type::where('type_status','1')->orderBy('type_id','desc')->get();
        
        // Seo  
        $url_canonical = $request->url();

        return view('pages.account.forgot_pass')->with(compact('cat_pro', 'brand_pro', 'type_pro', 'url_canonical'));
    }

    public function recover_pass(Request $request){
        $this->validate($request, [
            'email_account' => 'required|email:rfc,dns|max:100'
        ],
        [
            'email_account.required' => 'Bạn chưa nhập email',
            'email_account.email' => 'Email bạn nhập chưa đúng',
        ]);
        $data = $request->all();

        $now = Carbon::now('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');

        $title_mail = "Lấy lại mật khẩu ".' '.$now;
        $cus = Customer::where('customer_email', '=', $data['email_account'])->get();
        
        foreach($cus as $key => $value){
            $customer_id = $value->customer_id;
        }

        if($cus){
            $count_customer = $cus->count();
            if($count_customer == 0){
                Toastr::error('Email chưa được đăng ký','');
                return redirect()->back();
            }else{
                $token_random = Str::random(6);
                $customer = Customer::find($customer_id);
                $customer->customer_token = $token_random;
                $customer->save();

                // send mail
                $to_email = $data['email_account']; //gửi tới email này
                $link_reset_pass = url('/cap-nhat-mat-khau?email='.$to_email.'&token='.$token_random);

                $data = array("name"=>$title_mail, "body"=>$link_reset_pass, 'email'=>$data['email_account']); //body của forgot_pass_notify.blade.php

                Mail::send('pages.account.forgot_pass_notify', ['data'=>$data], function($message) use ($title_mail, $data){
                    $message->to($data['email'])->subject($title_mail);
                    $message->from($data['email'], $title_mail);
                });

                Toastr::success('Gửi mail thành công, vui lòng kiểm tra mail của bạn','');
                return redirect()->back();
            }
        }
    }

    public function update_new_pass(Request $request){
        $cat_pro = Category::where('category_status','1')->orderBy('category_id','desc')->get();
        $brand_pro = Brand::where('brand_status','1')->orderBy('brand_id','desc')->get();
        $type_pro = Type::where('type_status','1')->orderBy('type_id','desc')->get();
        
        // Seo  
        $url_canonical = $request->url();

        return view('pages.account.new_pass')->with(compact('cat_pro', 'brand_pro', 'type_pro', 'url_canonical'));
    }

    public function up_new_pass(Request $request){
        $this->validate($request, [
            'pass_account' => 'required|min:5',
            're_pass_account' => 'required|same:pass_account'
        ],
        [
            'pass_account.required' => 'Bạn chưa nhập mật khẩu',
            'pass_account.min' => 'Mật khẩu của bạn quá yếu',
            're_pass_account.required' => 'Bạn chưa nhập lại mật khẩu',
            're_pass_account.same' => 'Mật khẩu nhập lại của bạn không trùng khớp',
        ]);

        $data = $request->all();
        $token_random = Str::random(6);

        $customer = Customer::where('customer_email', '=', $data['email'])->where('customer_token', '=', $data['token'])->get();
        $count = $customer->count();
        if($count > 0){
            foreach($customer as $key => $cus){
                $customer_id = $cus->customer_id;
            }

            $reset = Customer::find($customer_id);
            $reset->customer_password = md5($data['pass_account']);
            $reset->customer_token = $token_random;
            $reset->save();

            Toastr::success('Cập nhật mật khẩu thành công','');
            return redirect('dang-nhap');
        }else{
            Toastr::error('Vui lòng nhập lại email vì link quá hạn','');
            return redirect('quen-mat-khau');
        }
    }

    public function logout(Request $request){
        $this->AuthLoginCus();

        Session::flush();
        return Redirect::to('/');
    }

    public function history_order(Request $request){
        $this->AuthLoginCus();

        $url_canonical = $request->url();
        
        // Thông tin khách hàng
        $result = Customer::where('customer_id', Session::get('customer_id'))->first(); 

        $cat_pro = Category::where('category_status','1')->orderBy('category_id','asc')->get();
        
        $order = Order::where('customer_id', Session::get('customer_id'))->orderBy('order_id','desc')->limit(10)->get();
        
        return view('pages.history.history_order')->with(compact('cat_pro','url_canonical', 'result', 'order'));
    }

    public function cancel_order(Request $request){
		$data = $request->all();

		$order = Order::where('order_code', $data['order_code'])->first();
		$order->order_status = 3;
		$order->order_storage = 1;
		$order->cancel_order = $data['lydo'];
		$order->save();
	}

    public function view_history(Request $request, $orderCode){
        $this->AuthLoginCus();

        // menu
        $url_canonical = $request->url();
        $cat_pro = Category::where('category_status','1')->orderBy('category_id','asc')->get();

        // Thông tin khách hàng
        $result = Customer::where('customer_id', Session::get('customer_id'))->first(); 
        
        // chi tiết sản phẩm
        $order_details = OrderDetails::with('product')->where('order_code',$orderCode)->get();
        
        // Thông tin giao hàng
        $order = Order::where('order_code',$orderCode)->first();
        
        $customer_id = $order->customer_id;
        $shipping_id = $order->shipping_id;
        $payment_id = $order->payment_id;
        $order_status = $order->order_status;
        
        $customer = Customer::where('customer_id',$customer_id)->first();
        $shipping = Shipping::where('shipping_id',$shipping_id)->first();
        $payment = Payment::where('payment_id',$payment_id)->first();

        // Tính tổng tiền
        $money = Order::where('order_code', $orderCode)->first();
        $coupon = Coupon::where('coupon_code', $money->order_coupon)->first();


        // foreach($order_details_product as $key => $order_d){

        // 	$product_coupon = $order_d->product_coupon;
        // }
        // if($product_coupon != 'no'){
        // 	$coupon = Coupon::where('coupon_code',$product_coupon)->first();
        // 	$coupon_condition = $coupon->coupon_condition;
        // 	$coupon_number = $coupon->coupon_number;
        // }else{
        // 	$coupon_condition = 2;
        // 	$coupon_number = 0;
        // }
        
        return view('pages.history.view_history')
            ->with(compact('cat_pro','url_canonical', 'result', 'order', 'order_details', 'shipping', 'payment',
            'money','coupon'));
    }

    public function load_cmt_rating(Request $request){
        $det_id = $request->ord_det_id;
        $pro_id = $request->product_id;
        
        $cmt = Comment::where('comment_pro_id', $pro_id)->where('ord_detail_id',$det_id)
            ->where('comment_parent_comment',0)->where('comment_status',0)->first();
        
        $output = '<textarea name="comment" class="comment_content">'.$cmt->comment.'</textarea>';

        echo $output;
    }
}
