<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Validator;
use App\Models\Mhtdata;
use App\Models\Tokenmap;
use App\Models\Eventdata;

class MhtController extends Controller
{

    public function addNewMht(Request $request)
    {
        /* validations check */
        $validator = Validator::make($request->all(), [
            'no_luggage' => 'required',
            // 'order_status_id' => 'required',
        ]);

        // dd($request->all());

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

        $Eventdata = Eventdata::latest('id')->first();

        /**
         * Step 1:getLastTokenNumber and bagNumber
         */
        //1) get last max number token number
        $getLastTokenNumber = Tokenmap::getLastTokenNumber($Eventdata);
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
                $mhtSave = Mhtdata::find($sgid);
                if($alternate_no){ $mhtSave->alternate_no = $alternate_no;}
                if($center_name){ $mhtSave->center_name = $center_name;}
                if($fname){ $mhtSave->fname = $fname;}
                if($mname){ $mhtSave->mname = $mname;}
                if($lname){ $mhtSave->lname = $lname;}
                $mhtSave->save();
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

        // 3) from step 1
        $next_bag_count = Tokenmap::getLastBagNumber($Eventdata, $sgid);
        $bagNumbers = '';
        for($i=0; $i< $noLuggage; $i++) {
            $bagNumbers .= ($next_bag_count + $i).',';
        }
        $bagsNumberString = rtrim($bagNumbers, ',');


        /**
         * Step 3: Add token
         */
        $addToken = Tokenmap::addToken($sgid,$finalTokenNumberString, $noLuggage, $Eventdata, $next_bag_count);

         /**
         * Step 4:getDataForPrint
         */
        $data = Mhtdata::getDataForPrint($sgid);

        /**
         * Step 5:TODO - Send response
         */

        $data['token_no'] = $finalTokenNumberString;
        $data['no_luggage'] = count(explode(',',$finalTokenNumberString));
        $data['bags_no'] = explode(',',$bagsNumberString);

        if(count( $data['bags_no']) != $data['no_luggage']){
            //TODO - add log error details in log file
            Log::info('Log message', array('context' => 'Bag and token count mismatch!','data'=>"sgid=$sgid, bagsNumberString=$bagsNumberString, finalTokenNumberString=$finalTokenNumberString, noLuggage=$noLuggage "));
            return response()
            ->json(['statusCode' => 400, 'error' => 'Error: Bag and token count mismatch!']);
        }

        return response()
        ->json(['statusCode' => 200, 'data' => $data]);
    }

    public function updateMht(Request $request)
    {
        /* validations check */
        $validator = Validator::make($request->all(), [
            'mhtid' => 'required',
            // 'order_status_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()
                ->json(['statusCode' => 429, 'data' => $validator->errors()]);
        }

        // $mhtid = $request->mhtid;
        $mhtnameArr = (isset($request->mhtname)) ? explode(' ',$request->mhtname) : [];
         if(count($mhtnameArr) > 0) {
            $lname = end($mhtnameArr);
            $fname = array_slice($mhtnameArr, 0, count($mhtnameArr)-2);
            $mname = array_slice($mhtnameArr, -2, 1);
         }
        $alternate_no = (isset($request->alternate_no)) ? $request->alternate_no : '';

        $IsMhtidExist = Mhtdata::where('mht_id',$request->mht_id )->orderBy('id','desc')->first();

        if(isset($IsMhtidExist->id)){
            $sgid = $IsMhtidExist->id;
            $mhtUpdate = Mhtdata::find($sgid);
            if($alternate_no){ $mhtUpdate->alternate_no = $alternate_no;}
            if($fname){ $mhtUpdate->fname = $fname;}
            if($mname){ $mhtUpdate->mname = $mname;}
            if($lname){ $mhtUpdate->lname = $lname;}
            $mhtUpdate->save();
        }

        $data = [
            'name' => $request->mhtname,
            'alternate_no' =>  $request->alternate_no
        ];
        return response()
        ->json(['statusCode' => 200, 'data' => $data]);
    }
}
