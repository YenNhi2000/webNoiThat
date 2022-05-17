<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;	//set time to false (created_at, updated_at)
    protected $fillable = [
        'customer_id', 'shipping_id', 'payment_id', 'order_total', 'order_status', 'order_code', 'order_coupon',
        'order_storage', 'created_at'   //các cột trong bảng shipping
    ];
    protected $primaryKey = 'order_id';	//khóa chính
    protected $table = 'tbl_order';		//tên bảng
}