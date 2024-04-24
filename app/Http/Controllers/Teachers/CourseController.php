<?php

namespace App\Http\Controllers\Teachers;

use App\Models\Course;
use App\Models\Subject;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function indexSubjects()
    {
        $subjects = auth()->user()->subjects;

        return view('teacher.courses.subject', ['subjects' => $subjects]);
    }

    public function show($subject)
    {

        $user = auth()->user();
        $courses=Course::where('teacher_id',$user->id)->where('subject_id',$subject)->get();
        $Fullsubject=Subject::findOrFail($subject);

        return view('teacher.courses.course', ['courses' => $courses,'subject'=>$Fullsubject]);
    }



    public function create(Request $request)
    {
        Session::put('form_type', 'create');


        $rules = [
            'title' => 'required',
            'file' => 'required',

        ];


        $messages= [
            'title.required' => 'Le champ du titre est obligatoire.',

            'file.required' => 'Le champ du fichier est obligatoire.',

            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'max' => [
                'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            ]

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with(['operation' => 'create']);
        }

        $course = new Course();

        $course->title = $request->input('title');
        $course->teacher_id = auth()->user()->id;
        $course->subject_id = $request->input('subject_id');


        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/courses', $fileName);

        $course->file = $fileName;

        $course->save();

        Session::flash('alert-success', 'success');
        return redirect()->route('teacher.courses.show', ['subject' => $course->subject_id])->with('success', 'Cours ajoutée avec succées !');

    }


    public function update(Request $request,$id)
    {
        Session::put('form_type', 'edit');
        session()->put('edit_course_id', $id);

        $course = Course::findOrFail($id);

        $rules = [
            'title' => 'required',
        ];


        $messages= [
            'title.required' => 'Le champ du titre est obligatoire.',

            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'max' => [
                'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            ]

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with(['operation' => 'create']);
        }


        $course->title = $request->input('title');
        if($file = $request->file('fileEdit')){

            $file = $request->file('fileEdit');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/courses', $fileName);
            $course->file = $fileName;
        }


        $course->save();

        Session::flash('alert-success', 'success');
        return redirect()->route('teacher.courses.show', ['subject' => $course->subject_id])->with('warning', 'Cours modifié avec succées !');
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


    public function destroy($id)
    {
    $course = Course::findOrFail($id);
    $subject=$course->subject_id;
    $course->delete();
    Session::flash('alert-danger', 'danger');
    // Return a success response with a success message
    return redirect()->route('teacher.courses.show', ['subject' => $subject])->with('danger', 'Cours supprimé avec succées !');
}

}
