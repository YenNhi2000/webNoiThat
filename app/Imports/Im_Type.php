<?php

namespace App\Imports;

use App\Models\Type;
use Maatwebsite\Excel\Concerns\ToModel;

class Im_Type implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Type([
            'type_name' => $row[0],
            'type_slug' => $row[1],
            'type_desc' => $row[2],
            'type_status' => $row[3],
        ]);
    }
}
