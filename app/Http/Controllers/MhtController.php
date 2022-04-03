<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Mhtdata;
use App\Models\Tokenmap;

class MhtController extends Controller
{

    public function addNewMht(Request $request)
    {
        /* validations check */
        $validator = Validator::make($request->all(), [
            'no_luggage' => 'required',
            // 'order_status_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()
                ->json(['statusCode' => 429, 'data' => $validator->errors()]);
        }

        $requestData = $request->all();
        $noLuggage = $requestData['no_luggage'];
        $alternate_no = (isset($requestData['alternate_no'])) ? $requestData['alternate_no'] : '';
        $center_name = (isset($requestData['center_name'])) ? $requestData['center_name'] : '';
        $city = (isset($requestData['city'])) ? $requestData['city'] : '';
        $fname = (isset($requestData['fname'])) ? $requestData['fname'] : '';
        $mname = (isset($requestData['mname'])) ? $requestData['mname'] : '';
        $lname = (isset($requestData['lname'])) ? $requestData['lname'] : '';
        $mht_id = (isset($requestData['mht_id'])) ? $requestData['mht_id'] : '';

        /**
         * Step 1:getLastTokenNumber
         */
        //1) get last max number token number
        $getLastTokenNumber = Tokenmap::getLastTokenNumber();
        if(is_numeric($getLastTokenNumber)) {
            $totenInit = $getLastTokenNumber + 1; //11
        } else {
            $totenInit = 1;
        }

        //2) create token number string as per luggage count
        $tokenNumbers = '';
        for($i=0; $i< $noLuggage; $i++) {
            $tokenNumbers .= ($totenInit + $i).',';
        }
        $finalTokenNumberString = rtrim($tokenNumbers, ',');

        /**
         * Step 2: Update Mhtdata
         */

            //check if mhid already exist. if exist then only get sgid // //update new data if entered in the form if required
            $IsMhtidExist = Mhtdata::where('mht_id',$request->mht_id )->orderBy('id','desc')->first();
            if(isset($IsMhtidExist->id)){
                $sgid = $IsMhtidExist->id;
                //update new data if entered in the form
                dd($requestData);
                $tokenSave = Mhtdata::where('id', $sgid)
                    ->update([
                        'alternate_no' => $alternate_no,
                        'center_name' => $center_name,
                        'fname' => $fname,
                        'mname' => $mname,
                        'lname' => $lname,
                    ]);
                // $finalTokenNumberString = $IsMhtidExist->token_no.",".$finalTokenNumberString;
            } else {
                //if not exist add new record
                $mhtdata = new Mhtdata();
                if($mht_id != '') {
                    $mhtdata->mht_id = $mht_id;
                } else {
                    //create $s_mht_id
                    $mhtdata->mht_id = "sg".uniqid();
                }
                $mhtdata->fname = $fname;
                $mhtdata->mname = $mname;
                $mhtdata->lname = $lname;
                // $mhtdata->whatsapp_no = $whatsapp_no;
                $mhtdata->alternate_no = $alternate_no;
                $mhtdata->center_name = $center_name;
                $mhtdata->city = $city;
                // $mhtdata->no_luggage = $noLuggage;
                // $mhtdata->token_no = $finalTokenNumberString;
                $mhtdata->save();
                if(isset($mhtdata)){
                    $sgid = $mhtdata->id;
                }
            }


        /**
         * Step 3: Add token
         */
        $addToken = Tokenmap::addToken($sgid,$finalTokenNumberString, $noLuggage);

         /**
         * Step 4:getDataForPrint
         */
        $data = Mhtdata::getDataForPrint($sgid);

        /**
         * Step 5:TODO - Send response
         */

        $data['token_no'] = $finalTokenNumberString;
        $data['no_luggage'] = count(explode(',',$finalTokenNumberString));

        return response()
        ->json(['statusCode' => 200, 'data' => $data]);
    }
}
