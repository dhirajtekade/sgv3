<?php

namespace App\Exports;

use App\Models\Mhtdata;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MhtdataExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings():array{
        return [
            'mht_id',
            'fname',
            'mname',
            'lname',
            'whatsapp_no',
            'alternate_no',
            'center_name',
            'city',
        ];
    }

    public function collection()
    {
       // return Mhtdata::all();
       return collect(Mhtdata::getMhtData());
    }

}
