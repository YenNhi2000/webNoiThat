<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public $timestamps = false;	//set time to false (created_at, updated_at)
    protected $filltable = [
        'brand_name', 'brand_slug', 'brand_desc', 'brand_status', 'brand_storage'	//các cột trong bảng Brand
    ];
    protected $primaryKey = 'brand_id';	//khóa chính
    protected $table = 'tbl_brand';		//tên bảng
}
