<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Students;

use App\Models\Mark;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarkController extends Controller
{
    public function indexSemesters()
    {
        $semesters = ['1','2'];
        return view('student.marks.semester', ['semesters' => $semesters]);
    }

    public function show($semester)
{
    $marks = Mark::where('student_id', auth()->user()->id)
                 ->whereHas('subject', function ($query) use ($semester) {
                     $query->where('semester', $semester);
                 })
                 ->get();

    return view('student.marks.show', ['marks' => $marks,'semester'=>$semester]);
}

}
