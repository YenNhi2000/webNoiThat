<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = false;	//set time to false (created_at, updated_at)
    protected $filltable = [
        'comment_name', 'comment', 'comment_date', 'comment_pro_id', 'comment_parent_comment', 'comment_status', 'ord_detail_id'	//các cột trong bảng Comment
    ];
    protected $primaryKey = 'comment_id';	//khóa chính
    protected $table = 'tbl_comment';		//tên bảng

    public function product(){
        return $this->belongsTo('App\Models\Product','comment_pro_id');    // 1 cmt chỉ thuộc 1 sp
    }
}
