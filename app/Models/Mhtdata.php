<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mhtdata extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "mhtdata";

    protected $fillable = [
                            // 'token_no',
                            // 'name',
                            'lname',
                            'fname',
                            'mname',
                            'center_name',
                            'whatsapp_no',
                            'alternate_no',
                            // 'no_luggage',
                            'mht_id',
                            'city',
                        ];

    public static function getDataForPrint($sgid) {

        $mhtdataJoin = Mhtdata::join('token_data', 'token_data.fk_mhtdata_id','=','mhtdata.id')
                                ->where('mhtdata.id', $sgid)
                                ->get();


        if(isset($mhtdataJoin[0])) {
            $data['no_luggage'] = count($mhtdataJoin);
            $whatsapp_no = $mhtdataJoin[0]->whatsapp_no;
            $alternate_no = $mhtdataJoin[0]->alternate_no;
            $data['have_mobile'] = ($whatsapp_no == '' && $alternate_no == '') ? 'no' : 'yes';
            $data['mht_id'] = $mhtdataJoin[0]->mht_id;

            foreach($mhtdataJoin as $key => $eachTokendataVal) {
                $each_token_no = $eachTokendataVal->each_token_no;
                $data['token_no'][$key] = $each_token_no;
                $data['qr'][$key] = asset('images/'.$each_token_no.'.png');
            }
        }

        return $data;
    }

}
