<?php

namespace App\Http\Controllers\Admin;
use App\Models\Field;
use App\Models\Timetable;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
        // return redirect()->route('admin.timetables.StudentsTimetable', ['department' => $timetable->department_id,'field'=>$timetable->field_id])->with('success', 'Emploi du temps ajoutée avec succées !');

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
