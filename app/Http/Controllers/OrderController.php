<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipping;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
session_start();

class OrderController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }

    public function all_order(){
        $this->AuthLogin();
        
        // $all_order = Order::where('order_storage','0')->orderBy('order_id','desc')->get();
        $all_order = DB::table('tbl_order')
            ->join('tbl_customers', 'tbl_order.customer_id', '=', 'tbl_customers.customer_id')
            ->where('order_storage','0')->orderBy('order_id','desc')->paginate(5);
            
        return view('admin.order.all_order')->with(compact('all_order'));
    }

    public function view_order($orderCode){
        $this->AuthLogin();
        
        // Thông tin giao hàng
        $order = Order::where('order_code', $orderCode)->get();
        foreach($order as $key => $val){
            $customer_id = $val->customer_id;
            $shipping_id = $val->shipping_id;
            $payment_id = $val->payment_id;
        }

        $customer = Customer::where('customer_id', $customer_id)->first();
        $shipping = Shipping::where('shipping_id', $shipping_id)->first();
        $payment = Payment::where('payment_id', $payment_id)->first();

        // Chi tiết đơn hàng
        $order_details = OrderDetails::with('product')->where('order_code', $orderCode)->get();
        
        // Tổng tiền đơn hàng
        $money = Order::where('order_code', $orderCode)->first();
        $coupon = Coupon::where('coupon_code', $money->order_coupon)->first();
        
        return view('admin.order.order_details')
            ->with(compact('order_details', 'customer', 'shipping', 'payment', 'money', 'coupon', 'order'));
    }

    public function update_order_qty(Request $request){
        $data = $request->all();

        // Cập nhật tình trạng đơn hàng
		$order = Order::find($data['order_id']);
		$order->order_status = $data['order_status'];
		$order->save();

        //send mail confirm
		// $now = Carbon::now('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
		// $title_mail = "Đơn hàng đã đặt được xác nhận".' '.$now;
		// $customer = Customer::where('customer_id',$order->customer_id)->first();
		// $data['email'][] = $customer->customer_email;

		
	  	//lay san pham
	  	
		// foreach($data['order_product_id'] as $key => $product_id){
        //     $product_mail = Product::find($product_id);
        //     foreach($data['quantity'] as $key2 => $qty){

        //         if($key==$key2){

		// 			$cart_array[] = array(
		// 				'product_name' => $product_mail['product_name'],
		// 				'product_price' => $product_mail['product_price'],
		// 				'product_qty' => $qty
		// 			);

		// 		}
		// 	}
		// }

		
	  	//lay shipping
	  	// $details = OrderDetails::where('order_code',$order->order_code)->first();

		// $fee_ship = $details->product_feeship;
		// $coupon_mail = $details->product_coupon;

	  	// $shipping = Shipping::where('shipping_id',$order->shipping_id)->first();
	  	
		// $shipping_array = array(
		// 	'fee_ship' =>  $fee_ship,
		// 	'customer_name' => $customer->customer_name,
		// 	'shipping_name' => $shipping->shipping_name,
		// 	'shipping_email' => $shipping->shipping_email,
		// 	'shipping_phone' => $shipping->shipping_phone,
		// 	'shipping_address' => $shipping->shipping_address,
		// 	'shipping_notes' => $shipping->shipping_notes,
		// 	'shipping_method' => $shipping->shipping_method

		// );
	  	// //lay ma giam gia, lay coupon code
		// $ordercode_mail = array(
		// 	'coupon_code' => $coupon_mail,
		// 	'order_code' => $details->order_code
		// );

		// // Mail::send('admin.confirm_order',  ['cart_array'=>$cart_array, 'shipping_array'=>$shipping_array ,'code'=>$ordercode_mail] , function($message) use ($title_mail,$data){
		// // 	      $message->to($data['email'])->subject($title_mail);//send this mail with subject
		// // 	      $message->from($data['email'],$title_mail);//send from this mail
		// // });


		// //order date
		// $order_date = $order->order_date;	
		
		// $statistic = Statistic::where('order_date',$order_date)->get();
		// if($statistic){
		// 	$statistic_count = $statistic->count();	
		// }else{
		// 	$statistic_count = 0;
		// }	

        // Cập nhật tình trạng đơn hàng
		if($order->order_status == 2){
			//them
			// $total_order = 0;
			// $sales = 0;
			// $profit = 0;
			// $quantity = 0;

			foreach($data['order_product_id'] as $key => $product_id){

				$product = Product::find($product_id);
				$product_quantity = $product->product_quantity;
				$product_sold = $product->product_sold;
				//them
				// $product_price = $product->product_price;
				// $product_cost = $product->price_cost;
				// $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

				foreach($data['quantity'] as $key2 => $qty){
					if($key == $key2){
						$pro_remain = $product_quantity - $qty;
						$product->product_quantity = $pro_remain;
						$product->product_sold = $product_sold + $qty;
						$product->save();

						//update doanh thu
						// $quantity+=$qty;
						// $total_order+=1;
						// $sales+=$product_price*$qty;
						// $profit = $sales - ($product_cost*$qty);
					}
				}
			}

		// 	//update doanh so db
		// 	if($statistic_count>0){
		// 		$statistic_update = Statistic::where('order_date',$order_date)->first();
		// 		$statistic_update->sales = $statistic_update->sales + $sales;
		// 		$statistic_update->profit =  $statistic_update->profit + $profit;
		// 		$statistic_update->quantity =  $statistic_update->quantity + $quantity;
		// 		$statistic_update->total_order = $statistic_update->total_order + $total_order;
		// 		$statistic_update->save();

		// 	}else{
		// 		$statistic_new = new Statistic();
		// 		$statistic_new->order_date = $order_date;
		// 		$statistic_new->sales = $sales;
		// 		$statistic_new->profit =  $profit;
		// 		$statistic_new->quantity =  $quantity;
		// 		$statistic_new->total_order = $total_order;
		// 		$statistic_new->save();
		// 	}
		}
    }

    public function delete_order($order_id){
        $this->AuthLogin();
        $del = Order::find($order_id);
        $del->order_storage = '1';
        $del->save();
        Toastr::success('Xóa đơn hàng thành công','');
        return redirect()->back();
    }

    public function order_storage(){
        $this->AuthLogin();

        $storage = Order::where('order_storage','1')->orderBy('order_id','desc')->get();
        return view('admin.order.storage')->with(compact('storage'));
    }

    public function restore_order($order_id){
        $this->AuthLogin();
        $restore = Order::find($order_id);
        $restore->order_storage = '0';
        $restore->save();
        Toastr::success('Khôi phục đơn hàng thành công','');
        return redirect()->back();
    }

    // in đơn hàng
    public function print_order($checkout_code){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->print_order_convert($checkout_code));
        return $pdf->stream();
    }

    public function print_order_convert($checkout_code){
        return $checkout_code;
    }
}