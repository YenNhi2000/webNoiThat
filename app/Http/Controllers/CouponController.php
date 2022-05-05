<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Customer;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
session_start();

class CouponController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }

    public function all_coupon(){
        $this->AuthLogin();

        $today = Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y');
        
        $all_coupon = Coupon::where('coupon_storage', '0')->orderby('coupon_id','desc')->paginate(5);
        return view('admin.coupon.all_coupon')->with(compact('all_coupon', 'today'));
    }

    public function save_coupon(Request $request){
        $this->AuthLogin();

        // $this->validate($request, [
        //     'name' => 'required|unique:tbl_coupon,coupon_name|max:255',
        //     'code' => 'required|max:255',
        //     'quantity' => 'required|numeric',
        //     'number' => 'required|numeric',
        // ],
        // [
        //     'name.required' => 'Bạn chưa nhập tên mã giảm giá',
        //     'cat_name.unique' => 'Tên danh mục đã có. Vui lòng điền tên khác',
        //     'cat_slug.required' => 'Bạn chưa nhập slug của sản phẩm',
        //     'cat_desc.required' => 'Bạn chưa nhập mô tả sản phẩm',
        // ]);

        $data = $request->all();
        $coupon = new Coupon();
        $coupon->coupon_name = $data['name'];
        $coupon->coupon_code = $data['code'];
        $coupon->date_start = $data['date_start'];
        $coupon->date_end = $data['date_end'];
        $coupon->coupon_quantity = $data['quantity'];
        $coupon->coupon_condition = $data['condition'];
        $coupon->coupon_number = $data['number'];
        $coupon->save();

        Toastr::success('Thêm mã giảm giá thành công','');
        return Redirect::to('all-coupon');
    }

    public function delete_coupon($coupon_id){
        $this->AuthLogin();
        $del_coupon = Coupon::find($coupon_id);
        $del_coupon->coupon_storage = '1';
        $del_coupon->save();
        Toastr::success('Xóa mã giảm giá thành công','');
        return redirect()->back();
    }

    public function coupon_storage(){
        $this->AuthLogin();
        $today = Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y');

        $storage = Coupon::where('coupon_storage','1')->orderBy('coupon_id','desc')->get();
        return view('admin.coupon.storage')->with(compact('storage', 'today'));
    }

    public function restore_coupon($coupon_id){
        $this->AuthLogin();
        $restore = Coupon::find($coupon_id);
        $restore->coupon_storage = '0';
        $restore->save();
        Toastr::success('Khôi phục mã giảm giá thành công','');
        return redirect()->back();
    }

    public function send_coupon($cou_id){
        $customer = Customer::all();
        $now = Carbon::now('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
        $title_mail = "Mã khuyến mãi ngày".' '.$now;

        $coupon = Coupon::where('coupon_id', $cou_id)->first();
        $cou = array(
            'start_coupon' => $coupon->date_start,
            'end_coupon' => $coupon->date_end,
            'coupon_qty' => $coupon->coupon_quantity,
            'coupon_condition' => $coupon->coupon_condition,
            'coupon_num' => $coupon->coupon_number,
            'coupon_code' => $coupon->coupon_code,
        );

        $data = [];
        foreach($customer as $cus){
            $data['email'][] = $cus->customer_email;
        }
        Mail::send('admin.coupon.send_coupon', ['coupon' => $cou], function($message) use ($title_mail, $data){
            $message->to($data['email'])->subject($title_mail);
            $message->from($data['email'], $title_mail);
        });

        Toastr::success('Đã gửi mã khuyến mãi đến khách hàng', '');
        return redirect()->back();
    }
}
