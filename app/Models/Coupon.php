<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    public $timestamps = false;	//set time to false (created_at, updated_at)
    protected $fillable = [
        'coupon_name', 'coupon_code', 'coupon_quantity', 'coupon_condition', 'coupon_number',
        'date_start', 'date_end', 'coupon_storage'	//các cột trong bảng Coupon
    ];
    protected $primaryKey = 'coupon_id';	//khóa chính
    protected $table = 'tbl_coupon';		//tên bảng
}
