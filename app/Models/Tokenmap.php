<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Eventdata;
// use DNS1D;
use Picqer;

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

    public static function getLastTokenNumber($Eventdata) {
        $event_id = $Eventdata->id;

        $latest_token_no = Tokenmap::orderBy('each_token_no', 'desc')->where('fk_event_id', $event_id)->take(1)->value('each_token_no');
        // $latest_token_no = Tokenmap::max('each_token_no');
        // $latest_token_no = Mhtdata::orderBy('token_no', 'desc')->value('token_no');
        // dd($latest_token_no);
        return $latest_token_no;
    }

    public static function getLastBagNumber($Eventdata, $sgid) {
        $event_id = $Eventdata->id;

        //TODO - total number shall be final (total luggage). And this shall update in current and previous tokenmap no_luggage count
        //get the total bags of this mhtid
        $next_bag_count = 1;

        // option 1: start from 1 if all previous are softdeleted
        // $mht_old_token_data = Tokenmap::where('fk_mhtdata_id', $sgid)->where('fk_event_id', $event_id)->get();//this will consider only non deleted, so next count will be according to that, it could start from 1 also if all previous others are checkedout/ soft deleted
        //    if(isset($mht_old_token_data) && count($mht_old_token_data) > 0) {
        //         $mht_old_bags_count = count($mht_old_token_data);
        //         $next_bag_count = $mht_old_bags_count+1;
        //     }

        //option2.1: start from next even considering number of previous deleted
        //$mht_old_token_data = Tokenmap::where('fk_mhtdata_id', $sgid)->where('fk_event_id', $event_id)->withTrashed()->get();//this will consider only non deleted, so next count will be according to that, it could start from 1 also if all previous others are checkedout/ soft deleted

        //option2.2: start from next even considering number of previous deleted
        $mht_old_token_data = Tokenmap::select('no_luggage')->where('fk_mhtdata_id', $sgid)->where('fk_event_id', $event_id)->orderBy('no_luggage','desc')->withTrashed()->first();
        if(isset($mht_old_token_data)) {
            $mht_old_bags_count = $mht_old_token_data['no_luggage'];
            $next_bag_count = $mht_old_bags_count+1;
        }
        return $next_bag_count;
    }

    public static function addToken($sgid,$newTokenNumber, $noLuggage, $Eventdata, $next_bag_count) {

        // $event_id = Eventdata::getlatestEventId();

        $event_id = $Eventdata->id;
        $eventName = $Eventdata->event_name;
        $department = $Eventdata->department;

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

            // $image_path  = self::QRCodeGenerator($sgid,$thisBagToken);
            // $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
            // $barcode = $generator->getBarcode(1, $generator::TYPE_CODE_128);

            // $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
            // file_put_contents('barcode.jpg', $generator->getBarcode('081231723897', $generator::TYPE_CODABAR));


            // $generator = new BarcodeGenerator('012345678', BarcodeType::TYPE_CODE_128, BarcodeRender::RENDER_JPG);

            // // Generates our default barcode with width=2, height=30, color=#000000
            // $generated = $generator->generate();

            // // Generates the same code with style updates
            // $generated = $generator->generate(4, 50, '#FFCC33');
            $barcodeValue = $sgid.'-'.$event_id.'-'.$thisBagToken;

            // $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
            // file_put_contents('barcode.png', $generator->getBarcode('123456789', $generator::TYPE_CODABAR));

            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
            file_put_contents("images/".$thisBagToken.".png", $generator->getBarcode($barcodeValue, $generator::TYPE_CODE_128));


            $TokenData = new Tokenmap();
            $TokenData->fk_mhtdata_id = $sgid;
            $TokenData->fk_event_id = $event_id;
            $TokenData->each_token_no = $thisBagToken;
            $TokenData->no_luggage = $next_bag_count; //not playing any role as of now
            // $TokenData->qr_path = $thisBagToken;
            // $TokenData->fk_mhtdata_id = ;
            $TokenData->save();

            $next_bag_count++;
        }

        return true;
    }

    public static function QRCodeGenerator($sgid,$thisBagToken) {
            /**
             * QR CODE (commented as of now )
             * use partialcheckoutscan in qr code only if we want to checkout direct from any device
             */
            // $url = \URL::to('/')."/partialcheckoutscan/$sgid/$thisBagToken";

            /**
             * Barcode code
             */
            // DNS1D::getBarcodeHTML($thisBagToken, 'CODABAR');
             // image path in the drive
            $image_path = "images/".$thisBagToken.".png";

            //
            // $qr_code_value = 'checkout-'.'';
            // $qr_code = \QrCode::size(300)
            // ->format('png')
            // ->generate($qr_code_value, public_path($image_path));
        return $image_path;
    }

    public static function getTodayStatusData(){
        $event_id = Eventdata::getlatestEventId();

        $total_bags_count_data = Tokenmap::orderBy('each_token_no', 'desc')->where('fk_event_id', $event_id)->withTrashed()->get();
        $todays_bag_status = [];
        if(count( $total_bags_count_data) > 0){
            $todays_bag_status['total_bags_count'] = count( $total_bags_count_data);

            $total_bags_checkin_data = Tokenmap::orderBy('each_token_no', 'desc')->where('fk_event_id', $event_id)->get();
            $total_bags_checkout_data = Tokenmap::orderBy('each_token_no', 'desc')->where('fk_event_id', $event_id)->onlyTrashed()->get();

            $todays_bag_status['total_bags_checkin'] = count( $total_bags_checkin_data);
            $todays_bag_status['total_bags_checkout'] = count( $total_bags_checkout_data);
        }

        return $todays_bag_status;
    }
}
