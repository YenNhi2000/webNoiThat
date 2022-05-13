<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;	//set time to false (created_at, updated_at)
    protected $filltable = [
        'product_name', 'product_slug', 'product_quantity', 'product_sold', 'product_desc', 'product_content', 'product_price','product_cost','product_image', 
        'cat_id', 'brand_id', 'type_id', 'product_views', 'product_status', 'product_storage', 'product_total_star', 'product_total_review', 'avg_star'	//các cột trong bảng product
    ];
    protected $primaryKey = 'product_id';	//khóa chính
    protected $table = 'tbl_product';		//tên bảng

    public function category(){
        return $this->belongsTo('App\Models\Category','cat_id');       //1sp thuộc 1 danh mục dựa trên cat_id của tbl_product
    }

    public function comment(){
        return $this->hasMany('App\Models\Comment');
    }
}
