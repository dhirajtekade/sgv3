<?php
use App\Models\Settings;
use App\Models\EventData;

if (! function_exists('getSettingConfig')) {
    function getSettingConfig() {
        $getSettingVars = \Settings::getConfigSetting();
        return $getSettingVars;

    }
}

if (! function_exists('getEventInfo')) {
    function getEventInfo() {
        $getEventInfo = \EventData::getlatestEventData();
        return $getEventInfo;
    }
}


