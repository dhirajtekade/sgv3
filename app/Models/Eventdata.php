<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventdata extends Model
{
    use HasFactory;

    protected $table = "eventdata";

    protected $fillable = [
                            // 'token_no',
                            // 'name',
                            'department',
                            'event_name',
                            'month',
                            'year',
                            'event_location',
                        ];

    public static function getlatestEventId() {
        $curerntEventId = Eventdata::all()->last()->id;
        return $curerntEventId;
    }

    public static function getlatestEventData() {
        $curerntEventId = Eventdata::latest()->first();
        return $curerntEventId;
    }

}
