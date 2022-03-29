<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;
use App\Models\Social;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Redirect;
session_start();

class AdminController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }
    
    public function validation(Request $request){
        return $this->validate($request, [
            'ad_email' => 'required|email:rfc,dns|max:100',
            'ad_password' => 'required|max:255',
        ],
        [
            'ad_email.required' => 'Bạn chưa nhập email',
            'ad_password.required' => 'Bạn chưa nhập mật khẩu',
            'ad_email.email' => 'Email bạn nhập chưa đúng',
        ]);
    }

    public function index(){
        return view('admin.account.admin_login');
    }   

    public function show_dashboard(){
        $this->AuthLogin();
        // $admin_id = Session::get('admin_id');
        // $info = Admin::where('admin_id',$admin_id)->get();
        return view('admin.dashboard'); //->with(compact('info'));
    }

    public function login_admin(Request $request){
        $this->validation($request);
        $data = $request->all();
        $admin_email = $data['ad_email'];
        $admin_password = md5($data['ad_password']);

        $result = Admin::where('admin_email', $admin_email)->where('admin_password', $admin_password)->first();    //first() lấy dòng đầu tiên
        if($result){
            Session::put('admin_name', $result->admin_name);
            Session::put('admin_id', $result->admin_id);
            Toastr::success('Đăng nhập thành công','');
            return Redirect::to('/dashboard');
        }else{
            Toastr::error('Email hoặc mật khẩu không đúng. Vui lòng nhập lại','');
            return redirect()->back();
        }
    }

    public function login_facebook(){
        return Socialite::driver('facebook')->redirect();
    }
    
    public function callback_facebook(){
        $provider = Socialite::driver('facebook')->user();
        dd($provider);
        
        // $account = Social::where('provider','facebook')->where('provider_user_id', $provider->getId())->first();
        // if($account){
        //     //login in vao trang quan tri
        //     $account_name = Admin::where('admin_id',$account->user)->first();
        //     Session::put('admin_name', $account_name->admin_name);
        //     Session::put('admin_id', $account_name->admin_id);
        //     return redirect('/dashboard')->with('message', 'Đăng nhập Admin thành công');
        // }else{
        
        //     $hieu = new Social([
        //         'provider_user_id' => $provider->getId(),
        //         'provider' => 'facebook'
        //     ]);
            
        //     $orang = Admin::where('admin_email', $provider->getEmail())->first();
            
        //     if(!$orang){
        //         $orang = Admin::create([
                
        //             'admin_name' => $provider->getName(),
        //             'admin_email' => $provider->getEmail(),
        //             'admin_password' => '',
        //             'admin_phone' => ''
                
        //         ]);
        //     }
        //     $hieu->login()->associate($orang);
        //     $hieu->save();
            
        //     $account_name = Admin::where('admin_id',$account->user)->first();
            
        //     Session::put('admin_name',$account_name->admin_name);
        //     Session::put('admin_id',$account_name->admin_id);
        //     return redirect('/dashboard')->with('message', 'Đăng nhập Admin thành công');
        // }
    }

    public function login_google(){
        return Socialite::driver('google')->redirect();
    }
    
    public function callback_google(){
        $users = Socialite::driver('google')->user();
        // return $users->id;
        $authUser = $this->findOrCreateUser($users,'google');
        // $account_name = Admin::where('admin_id',$authUser->user)->first();
        // Session::put('admin_login',$account_name->admin_name);
        // Session::put('admin_id',$account_name->admin_id);
        // Toastr::success('Đăng nhập thành công','');
        // return redirect('/dashboard');
        dd($authUser);  
    }

    public function findOrCreateUser($users, $provider){
        $authUser = Social::where('provider_user_id', $users->id)->first();
        
        // if($authUser){
        //     return $authUser;
        // }
        
        // $hieu = new Social([
        //     'provider_user_id' => $users->id,
        //     'provider' => strtoupper($provider)
        // ]);

        // $orang = Admin::where('admin_email', $users->email)->first();
        
        // if(!$orang){
        //     $orang = Admin::create([
        //         'admin_name' => $users->name,
        //         'admin_email' => $users->email,
        //         'admin_password' => '',
        //         'admin_phone' => '',
        //         // 'admin_token' => ''
        //     ]);
        // }
        // $hieu->login()->associate($orang);
        // $hieu->save();
        
        // $account_name = Admin::where('admin_id',$authUser->user)->first();
        // Session::put('admin_login',$account_name->admin_name);
        // Session::put('admin_id',$account_name->admin_id);
        // Toastr::success('Đăng nhập thành công','');
        // return redirect('/dashboard');
    }
        
    public function change_pass(){
        return view('admin.account.change_pass');
    }

    public function confirm_pass(Request $request){
        $this->validate($request, [
            'old_pass' => 'required',
            'new_pass' => 'required|min:5',
            're_new_pass' => 'required|same:new_pass',
        ],
        [
            'old_pass.required' => 'Bạn chưa nhập mật khẩu hiện tại',
            'new_pass.required' => 'Bạn chưa nhập mật khẩu mới',
            'new_pass.min' => 'Mật khẩu mới của bạn quá yếu',
            're_new_pass.required' => 'Bạn chưa nhập lại mật khẩu mới',
            're_new_pass.same' => 'Mật khẩu nhập lại của bạn không trùng khớp',
        ]);

        $data = $request->all();
        $ad_id = Session::get('admin_id');

        $reset = Admin::find($ad_id);
        if (md5($data['old_pass']) == $reset->admin_password){
            if (md5($data['new_pass']) != $reset->admin_password){
                $reset->admin_password = md5($data['new_pass']);
                $reset->save();

                Toastr::success('Đổi mật khẩu thành công','');
                return redirect('change-password');
            }else{
                Toastr::error('Bạn vừa nhập mật khẩu hiện tại. Vui lòng nhập mật khẩu khác','');
                return redirect()->back();
            }
        }else{
            Toastr::error('Mật khẩu sai. Vui lòng nhập lại mật khẩu hiện tại','');
            return redirect()->back();
        }
    }

    public function logout(Request $request){
        $this->AuthLogin();
        Session::put('admin_name', null);
        Session::put('admin_id', null);
        return Redirect::to('/admin');
    }

    public function forgot_pass(){
        return view('admin.account.forgot_pass');
    }

    public function recover_pass(Request $request){
        $this->validate($request, [
            'email_recover' => 'required|email:rfc,dns|max:100'
        ],
        [
            'email_recover.required' => 'Bạn chưa nhập email',
            'email_recover.email' => 'Email bạn nhập chưa đúng',
        ]);

        $data = $request->all();

        $now = Carbon::now('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');

        $title_mail = "Lấy lại mật khẩu ".' '.$now;
        $ad = Admin::where('admin_email', '=', $data['email_recover'])->get();
        
        foreach($ad as $key => $value){
            $admin_id = $value->admin_id;
        }

        if($ad){
            $count_admin = $ad->count();
            if($count_admin == 0){
            Toastr::error('Email chưa được đăng ký !!!','');
            return redirect()->back();
            }else{
                $token_random = Str::random(6);
                $admin = Admin::find($admin_id);
                $admin->admin_token = $token_random;
                $admin->save();

                // send mail
                $to_email = $data['email_recover']; //gửi tới email này
                $link_reset_pass = url('/update-new-pass?email='.$to_email.'&token='.$token_random);

                $data = array("name"=>$title_mail, "body"=>$link_reset_pass, 'email'=>$data['email_recover']); //body của forgot_pass_notify.blade.php

                Mail::send('admin.account.forgot_pass_notify', ['data'=>$data], function($message) use ($title_mail, $data){
                    $message->to($data['email'])->subject($title_mail);
                    $message->from($data['email'], $title_mail);
                });

                Toastr::success('Gửi mail thành công, vui lòng kiểm tra mail của bạn','');
                return redirect()->back();
            }
        }
    }

    public function update_new_pass(Request $request){
        // Seo  
        // $url_canonical = $request->url();

        return view('admin.account.new_pass');  //->with(compact('cat_pro', 'brand_pro', 'type_pro', 'url_canonical'));
    }

    public function up_pass_admin(Request $request){
        $this->validate($request, [
            'new_pass' => 'required|min:5',
            're_new_pass' => 'required|same:new_pass'
        ],
        [
            'new_pass.required' => 'Bạn chưa nhập mật khẩu mới',
            'new_pass.min' => 'Mật khẩu của bạn quá yếu',
            're_new_pass.required' => 'Bạn chưa nhập lại mật khẩu',
            're_new_pass.same' => 'Mật khẩu nhập lại của bạn không trùng khớp',
        ]);

        $data = $request->all();
        $token_random = Str::random(6);

        $admin = Admin::where('admin_email', '=', $data['email'])->where('admin_token', '=', $data['token'])->get();
        $count = $admin->count();
        if($count > 0){
            foreach($admin as $key => $ad){
                $admin_id = $ad->admin_id;
            }

            $reset = Admin::find($admin_id);
            $reset->admin_password = md5($data['new_pass']);
            $reset->admin_token = $token_random;
            $reset->save();

            Toastr::success('Cập nhật mật khẩu thành công','');
            return redirect('admin');
        }else{
            Toastr::error('Vui lòng nhập lại email vì link quá hạn','');
            return redirect('forgot-pass');
        }
    }
}
