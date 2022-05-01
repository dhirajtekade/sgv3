<?php

namespace App\Imports;

use App\Models\Mhtdata;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class MhtdataImport implements ToModel,WithHeadingRow, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Mhtdata([
            'mht_id' => $row['mht_id'],
            'fname' => $row['fname'],
            'mname' => $row['mname'],
            'lname' => $row['lname'],
            'center_name' => $row['center_name'],
            'whatsapp_no' => $row['whatsapp_no'],
            'alternate_no' => $row['alternate_no'],
            'city' => $row['city'],
        ]);
    }

    /**
     * method for withupserts
     * Reference :https://docs.laravel-excel.com/3.1/imports/model.html
     */
    public function uniqueBy()
    {
        return 'mht_id';
    }
}
