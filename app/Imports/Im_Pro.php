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
            'product_desc' => $row[3],
            'product_content' => $row[4],
            'product_price' => $row[5],
            'product_cost' => $row[6],
            'product_image' => $row[7],
            'cat_id' => $row[8],
            'brand_id' => $row[9],
            'type_id' => $row[10],
            'product_status' => $row[11],
        ]);
    }
}