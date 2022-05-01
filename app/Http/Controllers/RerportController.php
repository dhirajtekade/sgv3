<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Excel;
use App\Imports\MhtdataImport;
use App\Exports\MhtdataExport;
use App\Models\Eventdata;

class RerportController extends Controller
{
    //
    public function report_page(){
        $eventdata = Eventdata::all();
        $current_event = Eventdata::getlatestEventData();
        $event_start_date = $current_event->event_start_date;
        $event_end_date = $current_event->event_end_date;
        // dd($event_start_date);
        return view('admin.report_page', compact('eventdata','event_start_date','event_end_date'));
    }

    public function mhtdata_import(Request $request){
        $requestAll = $request->all();
        $validator = Validator::make($request->all(), [
            'file' => 'required',
            // 'order_status_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please select valid Excel file!');
        }

        Excel::import(new MhtdataImport, $request->file);
        // return "Records imorted successfully";
        // return Redirect::back()->withErrors(['msg' => 'The Message']);
        return redirect()->back()->with('success', 'Records imorted successfully!');
    }

    public function exportIntoExcel() {
        return Excel::download(new MhtdataExport, "exportdata_".date("Y-m-d").".xlsx");
    }

    public function export_report(Request $request) {
        $requestAll = $request->all();
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            // 'order_status_id' => 'required',
        ]);
        $eventData = Eventdata::getEventById($requestAll['event_id']);
        $eventName = $eventData->event_name;
        $eventName = str_replace(' ','-', $eventName);

        return Excel::download(new MhtdataExport, "export_report_".date("Y-m-d").".xlsx");

    }
}
