<?php

namespace App\Http\Controllers\Admin;
use App\Models\ExamCalendar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ExamCalendarController extends Controller
{


    public function index()
    {
        $calendars = ExamCalendar::all();
        return view('admin.exam_calendar.index', ['calendars'=>$calendars]);
    }


    public function indexTeacher()
{
    $latestCalendar=ExamCalendar::first();
    return view('teacher.exam_calendar.index',['latestCalendar'=>$latestCalendar ]);
}

public function indexStudent()
{
    $latestCalendar  = ExamCalendar::latest()->first();
    return view('student.exam_calendar.index', ['latestCalendar'=>$latestCalendar ]);
}

    public function create(Request $request)
    {

        Session::put('form_type', 'create');


        $rules = [
            'file' => 'required',

        ];


        $messages= [
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

        $calendar = new ExamCalendar();

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/calendars', $fileName);


        $calendar->file = $fileName;

        $calendar->save();

        Session::flash('alert-success', 'success');
        return redirect()->route('admin.calendars.index')->with('success', 'Calendrier ajoutée avec succées !');

    }



    public function update(Request $request,$id)
    {
        Session::put('form_type', 'edit');
        session()->put('edit_calendar_id', $id);

        $calendar = ExamCalendar::findOrFail($id);

        $rules = [

        ];


        $messages= [

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


        if($file = $request->file('fileEdit')){

            $file = $request->file('fileEdit');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/calendars', $fileName);
            $calendar->file = $fileName;
        }


        $calendar->save();

        Session::flash('alert-success', 'success');
        return redirect()->route('admin.calendars.index')->with('warning', 'Calendrier mis a jours avec succées !');
    }

    public function destroy($id)
    {
    $calendar = ExamCalendar::findOrFail($id);
    $calendar->delete();
    Session::flash('alert-danger', 'danger');
    // Return a success response with a success message
    return redirect()->route('admin.calendars.index')->with('danger', 'Calendrier supprimé avec succées !');
}



    public function download($fileName)
    {
        // Get the file path
        $filePath = storage_path('app/public/calendars/' . $fileName);

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
