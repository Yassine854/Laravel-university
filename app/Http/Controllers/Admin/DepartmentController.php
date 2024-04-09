<?php

namespace App\Http\Controllers\Admin;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments=Department::all();
        return view('admin.departments.index', ['departments' => $departments]);
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
                ->withInput()
                ->with(['operation' => 'create']);
        }



        $department = new Department();
        $department->name = $request->input('name');
        $department->save();
        Session::flash('alert-success', 'success');
        return redirect()->route('admin.departments.index')->with('success', 'Département ajouté avec succées !');
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
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Session::put('form_type', 'edit');
        session()->put('edit_department_id', $id);

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

        $department = Department::findOrFail($id);
        $department->name = $request->input('name');

        $department->save();
        Session::flash('alert-warning', 'warning');

        return redirect()->route('admin.departments.index')->with('warning', 'Département modifié avec succées !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
    $department->delete();
    Session::flash('alert-danger', 'danger');
    // Return a success response with a success message
    return redirect()->route('admin.departments.index')->with('danger', 'Département supprimé !');
    }
}
