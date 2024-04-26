<?php

namespace App\Http\Controllers\Students;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function indexSubjects()
    {
        $subjects = Subject::where('field_id',auth()->user()->field_id)->get();

        return view('student.courses.subject', ['subjects' => $subjects]);
    }


    public function show($subject)
    {

        $courses=Course::where('subject_id',$subject)->get();
        $Fullsubject=Subject::findOrFail($subject);

        return view('student.courses.show', ['courses' => $courses,'subject'=>$Fullsubject]);
    }





    public function downloadCourse($fileName)
    {
        // Get the file path
        $filePath = storage_path('app/public/courses/' . $fileName);
        // Check if the file exists
        if (file_exists($filePath)) {
            // Return the file path
            return response()->file($filePath);
        } else {
            // If the file does not exist, return a 404 response
            abort(404);
        }
    }

}
