<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use App\DataTables\MhtdataDataTable;
use App\Models\Mhtdata;
use App\Models\Eventdata;
use App\Models\Tokenmap;
use DataTables;
use Picqer;

class SgdataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Function to list the tokens given to mahatmas
     */
    public function sglist(Request $request)
    {
        if ($request->ajax()) {
            // $data = User::select('*');
            //get eventid
            $event_id = Eventdata::getlatestEventId();
            $data = Mhtdata::select('mhtdata.id as id','mhtdata.mht_id as mht_id','mhtdata.alternate_no as alternate_no',  DB::raw('GROUP_CONCAT(token_data.each_token_no) as `token_no`'))
            ->join('token_data', 'token_data.fk_mhtdata_id', 'mhtdata.id')
            ->where('token_data.fk_event_id', $event_id)
            ->whereNull('mhtdata.deleted_at')
            ->whereNull('token_data.deleted_at')
            // ->groupBy('token_data.fk_mhtdata_id','id','mht_id','alternate_no','no_luggage')
            ->groupBy('id','mht_id','alternate_no')

            ->get();//latest()->get();
            // dd($data);
            //  dd($data);
             $dd =  Datatables::of($data)
                     ->addIndexColumn()
                     ->removeColumn('id')
                    //  ->removeColumn('whatsapp_no')
                     // ->removeColumn('created_at')
                     // ->removeColumn('updated_at')
                    //  ->addColumn('action', function($row){
                    //         $print = '<button class="printButton btn btn-primary btn-sm mr-2" id="printId_'.$row->id.'" disabled>Print</button>';
                    //         $edit = '';//'<button class="editButton btn btn-info btn-sm" id="editId_'.$row->id.'" disabled>Edit</button>';
                    //         $send = '<a href="" class="sendMsgButton btn btn-info btn-sm mr-2" id="sendId_'.$row->id.'" style="display:none;">Send Msg</a>';
                    //         $checkout = '<a  class="checkoutButton btn btn-success btn-sm mr-2" id="checkoutId_'.$row->id.'">Checkout</a>';
                    //         $partialcheckout = '';//'<a  class="partialcheckoutButton btn btn-info btn-sm mr-2" id="partialcheckoutId_'.$row->id.'">Partial Checkout</a>';
                    //         $action = '<div class="btn-group" role="group">';
                    //         $action .= $print.$partialcheckout.$checkout.$edit.$send;
                    //         $action .= '</div>';
                    //          return $action;
                    //  })
                    //  ->editColumn('no_luggage', function($data) {
                    //      // return 'Hi ' . $data->no_luggage . '!';
                    //      return '<input type="text" name="no_luggage" class="no_luggage numericCheck" id="noluggage_'.$data->id.'" size="5">';
                    //  })
                     ->editColumn('alternate_no', function($data) {
                         // return 'Hi ' . $data->no_luggage . '!';
                         $number = ($data->alternate_no) ? $data->alternate_no : $data->whatsapp_no;
                         return $number;
                         //return '<input type="text" name="no_luggage" class="no_luggage numericCheck" id="noluggage_'.$data->id.'" size="5">';
                     })
                    //  ->editColumn('token_no', function($data) {
                    //      // return 'Hi ' . $data->no_luggage . '!';
                    //      return '<span class="token_no" id="tokenno_'.$data->id.'" >'.$data->token_no.'</span>';
                    //  })

                    //  ->editColumn('name', function($data) {
                    //      // dd($data);
                    //      // return 'Hi ' . $data->no_luggage . '!';
                    //      return $data->fname." ".$data->mname." ".$data->lname;
                    //  })
                    //  ->rawColumns(['action','no_luggage','token_no'])
                     ->rawColumns(['action'])
                     ->make(true);
             return $dd;
                    // dd($dd);
         }

        //get total bag status count
         $getTodayStatusData = TokenMap::getTodayStatusData();

        return view('operator.sglist', compact('getTodayStatusData'));
    }

    /**
     *
     * select `mhtdata`.`id` as `id`, `mhtdata`.`mht_id` as `mht_id`, CONCAT_WS(' ', fname, mname, lname) as name, `mhtdata`.`whatsapp_no` as `whatsapp_no`, `mhtdata`.`alternate_no` as `alternate_no`,
     * (select COUNT(token_data.id) as total_bags from `token_data` where `token_data`.`deleted_at` is null and `token_data`.`fk_mhtdata_id` = `mhtdata`.`id` )
     *  from `mhtdata`
     * left join `token_data` on `token_data`.`fk_mhtdata_id` = `mhtdata`.`id`
     *  where `mhtdata`.`deleted_at` is null
     *  group by `id`, `mht_id`, `fname`, `mname`, `lname`, `whatsapp_no`, `alternate_no`;
     */
    public function searchResult(Request $request) {

        $mhtid = $request->mhtid;
        $mobile = $request->mobile;
        $any = $request->any;

        if($mhtid !='' || $mobile !='' || $any !='') {
            $searchQuery = Mhtdata::select('mhtdata.id as id','mhtdata.mht_id as mht_id', DB::raw('CONCAT_WS(\' \', fname, mname, lname) as name'), 'mhtdata.whatsapp_no as whatsapp_no', 'mhtdata.alternate_no as alternate_no',
           DB::raw('(select COUNT(token_data.id) from `token_data` where `token_data`.`deleted_at` is null and `token_data`.`fk_mhtdata_id` = `mhtdata`.`id`) as total_bags'))
            ->leftjoin('token_data', 'token_data.fk_mhtdata_id','=','mhtdata.id');
            if($mhtid){
                $searchQuery = $searchQuery->where('mhtdata.mht_id','like', $mhtid.'%');
            }
            if($mobile){
                $searchQuery = $searchQuery->where('mhtdata.alternate_no','like', $mobile.'%')->orWhere('mhtdata.whatsapp_no', 'like',$mobile.'%');
            }
            if($any){
                $searchQuery = $searchQuery->where('mhtdata.mht_id','like', $any.'%')
                                ->orWhere('mhtdata.fname', 'like',$any.'%')
                                ->orWhere('mhtdata.mname', 'like',$any.'%')
                                ->orWhere('mhtdata.lname', 'like',$any.'%')
                                ->orWhere('mhtdata.alternate_no', 'like',$any.'%')
                                // ->orWhere('center_name', 'like',$any.'%')
                                // ->orWhere('city', 'like',$any.'%')
                                ->orWhere('mhtdata.whatsapp_no', 'like',$any.'%');
            }
            $searchResult = $searchQuery->groupBy('id','mht_id', 'fname', 'mname', 'lname','whatsapp_no', 'alternate_no');
            $searchResult = $searchQuery->get();

            return response()->json($searchResult,200);

        } else {//hide search result table
            return response()->json('-1',200);
        }
    }

    /**
     *
     */
    public function codetest(Request $request) {

        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        file_put_contents('barcode1.png', $generator->getBarcode('123456789', $generator::TYPE_CODABAR));


        file_put_contents('barcode2.png', $generator->getBarcode('123456789', $generator::TYPE_RMS4CC));

        file_put_contents('barcode3.png', $generator->getBarcode('123456789', $generator::TYPE_KIX));
        file_put_contents('barcode4.png', $generator->getBarcode('123456789', $generator::TYPE_IMB));
        file_put_contents('barcode5.png', $generator->getBarcode('123456789', $generator::TYPE_CODE_11));
        file_put_contents('barcode6.png', $generator->getBarcode('123456789', $generator::TYPE_PHARMA_CODE));
        file_put_contents('barcode7.png', $generator->getBarcode('123456789', $generator::TYPE_CODE_128));


        // $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
        //     file_put_contents("images/".$thisBagToken.".jpg", $generator->getBarcode($barcodeValue, $generator::TYPE_CODABAR));

        // $generator = new Picqer\Barcode\BarcodeGeneratorJPG();

        // // file_put_contents('barcode.jpg', $generator->getBarcode('081231723897', $generator::TYPE_CODABAR));
        // // $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        // $generator1 = $generator->getBarcode('0123456789', $generator::TYPE_CODE_128);
        // dd($generator1);
        // $generator = new BarcodeGenerator('012345678', BarcodeType::TYPE_CODE_128, BarcodeRender::RENDER_JPG);

    }

    /**
     * checkout all tokens/bags by mhtid
     */
    public function checkout(Request $request){
        $id = $request->id;

        Tokenmap::where('fk_mhtdata_id',$id)->delete();

        return response()->json($id,200);

    }

    /**
     * for special access to scan and all token checkout in one go. scan one and checkout all
     */
    public function checkoutallinone(Request $request) {
        $mhtid = $request->mhtid;
        $sgid = Mhtdata::where('mht_id', $mhtid)->value('id');
        Tokenmap::where('fk_mhtdata_id',$sgid)->delete();
        // return response()->json("Checkout all done for mhtid = $mhtid",200);
        $data = [
            'message' => 'checkout success',
            'mhtid' => $mhtid,
        ];
        return response()
        ->json(['statusCode' => 200, 'data' => $data]);
    }

    public function partialcheckout(Request $request) {

        $requestAll = $request->all();
        $validator = Validator::make($request->all(), [
            'sgid' => 'required',
            'eventid' => 'required',
            'token_no' => 'required',
            // 'order_status_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()
                ->json(['statusCode' => 429, 'data' => $validator->errors()]);
        }

        $fk_mhtdata_id = $requestAll['sgid'];
        $fk_event_id = $requestAll['eventid'];
        $token_no = $requestAll['token_no'];

        //check if A is coming at start and end
        if(!is_numeric($fk_mhtdata_id)) {
            $fk_mhtdata_id = str_replace('A','', $fk_mhtdata_id);
        }
        if(!is_numeric($token_no)) {
            $token_no = str_replace('A','', $token_no);
        }


        if ($token_no) {
            //TODO - check if already checked out
            $isAlreadyDeleted = Tokenmap::where('each_token_no', $token_no)->where('fk_mhtdata_id',$fk_mhtdata_id )->where('fk_event_id', $fk_event_id)->onlyTrashed()->first();

            if($isAlreadyDeleted) {
                $data = [
                    'message' => 'Already checkout',
                    'token' => $token_no,
                ];
                return response()
                ->json(['statusCode' => 200, 'data' => $data]);
            } else {
                //if not then checkout
                Tokenmap::where('each_token_no', $token_no)->where('fk_mhtdata_id',$fk_mhtdata_id )->where('fk_event_id', $fk_event_id)->delete();
                //TODO - pusher notification
                // PusherFactory::make()->trigger('my-event', 'myevent',['data'=>$data]);
                // $data['user'] = \Auth::user()->name;
                // $data['tokenDeleted'] = $tokenToDelete;
                // PusherFactory::make()->trigger('checkout-toastr', 'checkouttoastr',['data'=>$data]);

                $data = [
                    'message' => 'checkout success',
                    'token' => $token_no,
                ];
                return response()
                ->json(['statusCode' => 200, 'data' => $data]);
            }
        } else {
            return response()->json("no token found",400);
        }

    }

    /**
     *
     */
    public function generateFinalPrint (Request $request) {;
        $data = $request->all();
        return view('operator.printtoken')->with('data', $data);
    }

    public function scanqr3() {
        return view('operator.scanqr3');
    }

    public function scanbymachine() {
        return view('operator.scanbymachine');
    }
}
