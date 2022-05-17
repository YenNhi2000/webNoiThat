<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class Im_Pro implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {            
        return new Product([
            'product_name' => $row[0],
            'product_slug' => $row[1],
            'product_quantity' => $row[2],
            'product_sold' => $row[3],
            'product_desc' => $row[4],
            'product_content' => $row[5],
            'product_price' => $row[6],
            'product_cost' => $row[7],
            'product_image' => $row[8],
            'cat_id' => $row[9],
            'brand_id' => $row[10],
            'type_id' => $row[11],
            'product_views' => $row[12], 
            'product_status' => $row[13], 
            'product_storage' => $row[14], 
            'product_total_star' => $row[15], 
            'product_total_review' => $row[16], 
            'avg_star' => $row[17],
        ]);
    }
}
