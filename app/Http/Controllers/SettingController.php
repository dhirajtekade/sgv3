<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\Eventdata;

class SettingController extends Controller
{

    public function setting_form() {
        $settings = Settings::getConfigSetting();
        return view('admin.setting_form', compact('settings'));
    }

    public function store_setting_form(Request $request) {
        $requestAll = $request->all();
        $settingUpdate = Settings::find(1);
        $settingUpdate->qr_code_print =$requestAll['qr_code_print'];
        $settingUpdate->show_token_number =$requestAll['show_token_number'];
        $settingUpdate->show_bag_number =$requestAll['show_bag_number'];
        $settingUpdate->save();

        return redirect()->back()->with('message', 'Settings Updated!');
    }

    public function eveninfo(){
        $eventinfo = Eventdata::getlatestEventData();
        return view('admin.eventinfo_form', compact('eventinfo'));
    }

    public function store_eveninfo(Request $request) {
        $requestAll = $request->all();

        $settingUpdate = new Eventdata;
        $settingUpdate->department =$requestAll['department'];
        $settingUpdate->event_name =$requestAll['event_name'];
        $settingUpdate->month =$requestAll['month'];
        $settingUpdate->year =$requestAll['year'];
        $settingUpdate->event_location =$requestAll['event_location'];
        $settingUpdate->save();

        return redirect()->back()->with('message', 'Settings Updated!');
    }
}
