<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Type;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
session_start();

class StaffController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/dashboard');
        }else{
            return Redirect::to('/admin')->send();
        }
    }

    public function all_staff(){
        $this->AuthLogin();
        $all_staff = Staff::where('staff_storage','0')->orderBy('staff_id','desc')->get();
        return view('admin.staff.all_staff')->with(compact('all_staff'));
    }

    public function delete_staff($staff_id){
        $this->AuthLogin();
        $del_cus = Staff::find($staff_id);
        $del_cus->staff_storage = '1';
        $del_cus->save();
        Toastr::success('Xóa nhân viên thành công', '');
        return redirect()->back();
    }

    public function staff_storage(){
        $this->AuthLogin();

        $storage = Staff::where('staff_storage','1')->orderBy('staff_id','desc')->get();
        return view('admin.staff.storage')->with(compact('storage'));
    }

    public function restore_staff($staff_id){
        $this->AuthLogin();
        $restore = Staff::find($staff_id);
        $restore->staff_storage = '0';
        $restore->save();
        Toastr::success('Khôi phục nhân viên thành công','');
        return redirect()->back();
    }
}
