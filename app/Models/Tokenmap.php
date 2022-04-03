<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Eventdata;

class Tokenmap extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "token_data";

    protected $fillable = [
        'fk_mhtdata_id',
        'fk_event_id',
        'each_token_no',
        'no_luggage',
        'qr_path',
        'checkout_status',
    ];

    public static function getLastTokenNumber() {
        $event_id = Eventdata::getlatestEventId();
        $latest_token_no = Tokenmap::orderBy('each_token_no', 'desc')->where('fk_event_id', $event_id)->take(1)->value('each_token_no');
        // $latest_token_no = Tokenmap::max('each_token_no');
        // $latest_token_no = Mhtdata::orderBy('token_no', 'desc')->value('token_no');
        // dd($latest_token_no);
        return $latest_token_no;
    }

    public static function addToken($sgid,$newTokenNumber, $noLuggage) {

        // $event_id = Eventdata::getlatestEventId();
        $Eventdata = Eventdata::latest('id')->first();
        $event_id = $Eventdata->id;
        $eventName = $Eventdata->event_name;
        $department = $Eventdata->department;

        //TODO - total number shall be final (total luggage). And this shall update in current and previous tokenmap no_luggage count

        //create QR image (for each token) and add each token in tokenmap
        $eachToken = explode(',', $newTokenNumber);
        foreach ($eachToken as $thisBagEachCount => $thisBagToken) {
            // $recordData = $eventName.' '.$department.''. 'Token No:'.$thisBagToken;
            $recordData = [
                'event_name' => $eventName,
                'department' => $department,
                'token_number' => $thisBagToken,
                'samanghar_id' => $sgid,
            ];

            $image_path  = self::QRCodeGenerator($sgid,$thisBagToken);

            $TokenData = new Tokenmap();
            $TokenData->fk_mhtdata_id = $sgid;
            $TokenData->fk_event_id = $event_id;
            $TokenData->each_token_no = $thisBagToken;
            $TokenData->no_luggage = $noLuggage;
            $TokenData->qr_path = $image_path;
            // $TokenData->fk_mhtdata_id = ;
            $TokenData->save();
        }

        return true;
    }

    public static function QRCodeGenerator($sgid,$thisBagToken) {
            /**
             * QR CODE (commented as of now )
             * use partialcheckoutscan in qr code only if we want to checkout direct from any device
             */
            // $url = \URL::to('/')."/partialcheckoutscan/$sgid/$thisBagToken";
            // image path in the drive
            $image_path = "images/".$thisBagToken.".png";

            //
            // $qr_code_value = 'checkout-'.'';
            // $qr_code = \QrCode::size(300)
            // ->format('png')
            // ->generate($qr_code_value, public_path($image_path));
        return $image_path;
    }
}
