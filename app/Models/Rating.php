<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public $timestamps = false;	//set time to false (created_at, updated_at)
    protected $filltable = [
        'product_id', 'rating', 'customer_id', 'ord_detail_id'  //các cột trong bảng Rating
    ];
    protected $primaryKey = 'rating_id';	//khóa chính
    protected $table = 'tbl_rating';		//tên bảng
    
    public function order_details(){
        return $this->belongsTo('App\Models\OrderDetails','ord_detail_id');    // 1 rating chỉ thuộc 1 sp
    }
}
