<?php

namespace App\Exports;

use App\Models\Tokenmap;
use App\Models\Mhtdata;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TokendataExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Tokenmap::all();
    }
}
