<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DataTables\MhtdataDataTable;
use App\Models\Mhtdata;
use App\Models\Eventdata;
use DataTables;
use Picqer;

class SgdataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sglist(Request $request)
    {
        if ($request->ajax()) {
            // $data = User::select('*');
            //get eventid
            $event_id = Eventdata::getlatestEventId();
            $data = Mhtdata::select('mhtdata.id as id','mhtdata.mht_id as mht_id','mhtdata.alternate_no as alternate_no', 'token_data.no_luggage as no_luggage', DB::raw('GROUP_CONCAT(token_data.each_token_no) as `token_no`'))
            ->join('token_data', 'token_data.fk_mhtdata_id', 'mhtdata.id')
            ->where('token_data.fk_event_id', $event_id)
            ->groupBy('token_data.fk_mhtdata_id','id','mht_id','alternate_no','no_luggage')
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

        return view('operator.sglist');
    }

    public function searchResult(Request $request) {

        $mhtid = $request->mhtid;
        $mobile = $request->mobile;
        $any = $request->any;

        if($mhtid !='' || $mobile !='' || $any !='') {
            $searchQuery = Mhtdata::select('id','mht_id', DB::raw('CONCAT_WS(\' \', fname, mname, lname) as name'), 'whatsapp_no', 'alternate_no');
            if($mhtid){
                $searchQuery = $searchQuery->where('mht_id','like', $mhtid.'%');
            }
            if($mobile){
                $searchQuery = $searchQuery->where('alternate_no','like', $mobile.'%')->orWhere('whatsapp_no', 'like',$mobile.'%');
            }
            if($any){
                $searchQuery = $searchQuery->where('mht_id','like', $any.'%')
                                ->orWhere('fname', 'like',$any.'%')
                                ->orWhere('mname', 'like',$any.'%')
                                ->orWhere('lname', 'like',$any.'%')
                                ->orWhere('alternate_no', 'like',$any.'%')
                                // ->orWhere('center_name', 'like',$any.'%')
                                // ->orWhere('city', 'like',$any.'%')
                                ->orWhere('whatsapp_no', 'like',$any.'%');
            }
            $searchResult = $searchQuery->get();

            return response()->json($searchResult,200);

        } else {//hide search result table
            return response()->json('-1',200);
        }
    }

    public function codetest(Request $request) {

        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        file_put_contents('barcode.png', $generator->getBarcode('123456789', $generator::TYPE_CODABAR));


        // $generator = new Picqer\Barcode\BarcodeGeneratorJPG();

        // // file_put_contents('barcode.jpg', $generator->getBarcode('081231723897', $generator::TYPE_CODABAR));
        // // $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        // $generator1 = $generator->getBarcode('0123456789', $generator::TYPE_CODE_128);
        // dd($generator1);
        // $generator = new BarcodeGenerator('012345678', BarcodeType::TYPE_CODE_128, BarcodeRender::RENDER_JPG);

    }
}
