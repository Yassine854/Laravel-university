<?php

namespace App\Http\Controllers\Admin;
use App\Models\Field;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $departments = Department::all();
    return view('admin.fields.departments', ['departments' => $departments]);
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Session::put('form_type', 'create');
        $rules = [
            'name' => 'required|string|max:255',
            'department_id' => 'required|max:255',
        ];

        $messages= [
            'name.required' => 'Le champ nom est obligatoire.',
            'department_id.required' => 'Le champ département est obligatoire.',
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



        $field = new Field();
        $field->name = $request->input('name');
        $field->department_id = $request->input('department_id');
        $field->save();
        Session::flash('alert-success', 'success');
        return redirect()->route('admin.fields.show', ['field' => $field->department_id])->with('success', 'Filiére ajoutée avec succées !');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    public function show($department)
{
    $fields = Field::where('department_id', $department)->with('department')->get();
    return view('admin.fields.show', ['fields' => $fields,'department'=>$department]);
}


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function edit(Field $field)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Session::put('form_type', 'edit');
        session()->put('edit_field_id', $id);

        $rules = [
            'name' => 'required|string|max:255',
        ];

        $messages= [
            'name.required' => 'Le champ nom est obligatoire.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'max' => [
                'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            ]

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $field = Field::findOrFail($id);
        $field->name = $request->input('name');
        $field->save();
        Session::flash('alert-warning', 'warning');

        return redirect()->route('admin.fields.show', ['field' => $field->department_id])->with('warning', 'Filiére modifiée avec succées !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $field = Field::findOrFail($id);
    $field->delete();
    Session::flash('alert-danger', 'danger');
    // Return a success response with a success message
    return redirect()->route('admin.fields.show', ['field' => $field->department_id])->with('danger', 'Filiére supprimée !');
}
}
