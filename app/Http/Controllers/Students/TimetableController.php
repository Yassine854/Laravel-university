<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Students;
use App\Http\Controllers\Controller;


use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index()
    {
        $timetable = Timetable::where('role_id', 2)->where('field_id', auth()->user()->field_id)->where('group', auth()->user()->groupe)->first();

        return view('student.timetable.index', ['timetable'=>$timetable]);
    }
}
