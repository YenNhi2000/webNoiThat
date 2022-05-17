<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistical extends Model
{
    public $timestamps = false;	//set time to false (created_at, updated_at)
    protected $fillable = [
        'order_date', 'sales', 'profit', 'quantity', 'total_order'	//các cột trong bảng Brand
    ];
    protected $primaryKey = 'statistical_id';	//khóa chính
    protected $table = 'tbl_statistical';		//tên bảng
}
