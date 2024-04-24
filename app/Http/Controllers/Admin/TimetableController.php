<?php

namespace App\Http\Controllers\Admin;
use App\Models\Field;
use App\Models\Timetable;
use App\Models\Department;
use Illuminate\Http\Request;
use Facade\FlareClient\Time\Time;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TimetableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexDepartments()
    {
        $departments = Department::all();
        return view('admin.timetables.student.departments', ['departments' => $departments]);
    }


    public function indexFields($department)
    {
        $fields = Field::where('department_id', $department)->with('department')->get();
        return view('admin.timetables.student.fields', ['fields' => $fields,'department'=>$department]);
    }

    public function indexStudentsTimetable($department,$field)
    {
        // dd($department);
        $timetables = Timetable::where('role_id', 2)->where('field_id', $field)->get();

        $departmentName=Department::where('id',$department)->first()->name ?? null;
        $fieldName=Field::where('id',$field)->first()->name ?? null;
        return view('admin.timetables.student.timetables', ['timetables'=>$timetables,'department' => $department,'field' => $field,'departmentName' => $departmentName,'fieldName'=>$fieldName]);
    }



    public function createStudentsTimetable(Request $request)
    {
        Session::put('form_type', 'create');


        $rules = [
            'department_id' => 'required',
            'field_id' => 'required',
            'group' => 'required',
            'file' => 'required',

        ];


        $messages= [
            'file.required' => 'Le champ du fichier est obligatoire.',
            'group.required' => 'Le champ du groupe est obligatoire.',

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

        $timetable = new Timetable();

        $timetable->role_id = '2';
        $timetable->department_id = $request->input('department_id');
        $timetable->field_id = $request->input('field_id');
        $timetable->group = $request->input('group');

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/timetables', $fileName);


        $timetable->file = $fileName;

        $timetable->save();

        Session::flash('alert-success', 'success');
        return redirect()->route('admin.timetables.StudentsTimetable', ['department' => $timetable->department_id,'field'=>$timetable->field_id])->with('success', 'Emploi du temps ajoutée avec succées !');

    }

    public function updateStudentsTimetable(Request $request,$department,$field,$id)
    {
        Session::put('form_type', 'edit');
        session()->put('edit_timetable_id', $id);

        $timetable = Timetable::findOrFail($id);

        $rules = [
            'department_id' => 'required',
            'field_id' => 'required',
            'group' => 'required',
        ];


        $messages= [
            'group.required' => 'Le champ du groupe est obligatoire.',

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


        $timetable->role_id = '2';
        $timetable->department_id = $request->input('department_id');
        $timetable->field_id = $request->input('field_id');
        $timetable->group = $request->input('group');
        if($file = $request->file('fileEdit')){

            $file = $request->file('fileEdit');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/timetables', $fileName);
            $timetable->file = $fileName;
        }


        $timetable->save();

        Session::flash('alert-success', 'success');
        return redirect()->route('admin.timetables.StudentsTimetable', ['department' => $department,'field'=>$field])->with('warning', 'Emploi du temps mis a jours avec succées !');
    }

    public function downloadTimetable($fileName)
    {
        // Get the file path
        $filePath = storage_path('app/public/timetables/' . $fileName);

        // Check if the file exists
        if (file_exists($filePath)) {
            // Return the file path
            return response()->file($filePath);
        } else {
            // If the file does not exist, return a 404 response
            abort(404);
        }
    }


    public function destroyStudentsTimetable($department,$field,$id)
    {
    $timetable = Timetable::findOrFail($id);
    $timetable->delete();
    Session::flash('alert-danger', 'danger');
    // Return a success response with a success message
    return redirect()->route('admin.timetables.StudentsTimetable', ['department' => $department,'field'=>$field])->with('danger', 'Emploi du temps supprimé avec succées !');
}




public function indexTeachersTimetable()
    {
        $timetables = Timetable::where('role_id', 3)->with('teacher')->get();
        $teachers=User::where('role_id', 3)->get();
        return view('admin.timetables.teacher.timetables', ['timetables'=>$timetables,'teachers' => $teachers]);
    }



    public function createTeachersTimetable(Request $request)
    {

        Session::put('form_type', 'create');


        $rules = [
            'teacher_id'=> 'required',
            'file' => 'required',

        ];


        $messages= [
            'teacher_id.required' => 'Le champ nom enseignant est obligatoire.',
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

        $timetable = new Timetable();

        $timetable->role_id = '3';
        $timetable->teacher_id = $request->input('teacher_id');

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/timetables', $fileName);


        $timetable->file = $fileName;

        $timetable->save();

        Session::flash('alert-success', 'success');
        return redirect()->route('admin.timetables.TeachersTimetable')->with('success', 'Emploi du temps ajoutée avec succées !');

    }



    public function updateTeachersTimetable(Request $request,$id)
    {
        Session::put('form_type', 'edit');
        session()->put('edit_timetable_id', $id);

        $timetable = Timetable::findOrFail($id);

        $rules = [
            'teacher_id' => 'required',

        ];


        $messages= [
            'teacher_id.required' => 'Le champ nom enseignant est obligatoire.',

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


        $timetable->role_id = '3';
        $timetable->teacher_id = $request->input('teacher_id');
        if($file = $request->file('fileEdit')){

            $file = $request->file('fileEdit');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/timetables', $fileName);
            $timetable->file = $fileName;
        }


        $timetable->save();

        Session::flash('alert-success', 'success');
        return redirect()->route('admin.timetables.TeachersTimetable')->with('warning', 'Emploi du temps mis a jours avec succées !');
    }

    public function destroyTeachersTimetable($id)
    {
    $timetable = Timetable::findOrFail($id);
    $timetable->delete();
    Session::flash('alert-danger', 'danger');
    // Return a success response with a success message
    return redirect()->route('admin.timetables.TeachersTimetable')->with('danger', 'Emploi du temps supprimé avec succées !');
}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Timetable  $timetable
     * @return \Illuminate\Http\Response
     */
    public function show(Timetable $timetable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Timetable  $timetable
     * @return \Illuminate\Http\Response
     */
    public function edit(Timetable $timetable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Timetable  $timetable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Timetable $timetable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Timetable  $timetable
     * @return \Illuminate\Http\Response
     */
    public function destroy(Timetable $timetable)
    {
        //
    }
}
