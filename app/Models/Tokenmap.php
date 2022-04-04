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

    public static function addToken($sgid,$newTokenNumber, $noLuggage, $Eventdata) {

        // $event_id = Eventdata::getlatestEventId();

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
            $barcodeValue = $event_id.'-'.$thisBagToken;

            $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
            file_put_contents("images/".$thisBagToken.".jpg", $generator->getBarcode($barcodeValue, $generator::TYPE_CODABAR));


            $TokenData = new Tokenmap();
            $TokenData->fk_mhtdata_id = $sgid;
            $TokenData->fk_event_id = $event_id;
            $TokenData->each_token_no = $thisBagToken;
            $TokenData->no_luggage = $noLuggage;
            // $TokenData->qr_path = $thisBagToken;
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
}
