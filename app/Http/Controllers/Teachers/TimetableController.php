<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Teachers;
use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimetableController extends Controller
{
    public function index()
    {
        // dd($department);
        $timetable = Timetable::where('role_id', 3)->where('teacher_id', auth()->user()->id)->first();

        return view('teacher.timetable.index', ['timetable'=>$timetable]);
    }
}
