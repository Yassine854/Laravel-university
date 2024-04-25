<?php

namespace App\Http\Controllers\Admin;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields = Field::with('department')->get();
        $subjects=Subject::all();
        return view('admin.subjects.index', ['subjects' => $subjects,'fields'=>$fields]);
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
            'field_id' => 'required',
            'semester' => 'required|string|max:255',


        ];

        $messages= [
            'name.required' => 'Le champ nom est obligatoire.',
            'semester.required' => 'Le champ semester est obligatoire.',
            'field_id.required' => 'Le champ filiére est obligatoire.',
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



        $subject = new Subject();
        $subject->name = $request->input('name');
        $subject->semester = $request->input('semester');

        $subject->field_id = $request->input('field_id');
        $subject->save();
        Session::flash('alert-success', 'success');
        return redirect()->route('admin.subjects.index')->with('success', 'Matiére ajoutée avec succées !');
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
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Session::put('form_type', 'edit');
        session()->put('edit_subject_id', $id);

        $rules = [
            'name' => 'required|string|max:255',
            'field_id' => 'required',
            'semester' => 'required|string|max:255',

        ];

        $messages= [
            'name.required' => 'Le champ nom est obligatoire.',
            'semester.required' => 'Le champ semester est obligatoire.',
            'field_id.required' => 'Le champ filiére est obligatoire.',
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

        $subject = Subject::findOrFail($id);
        $subject->name = $request->input('name');
        $subject->semester = $request->input('semester');
        $subject->field_id = $request->input('field_id');

        $subject->save();
        Session::flash('alert-warning', 'warning');

        return redirect()->route('admin.subjects.index')->with('warning', 'Matiére modifiée avec succées !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    $subject = Subject::findOrFail($id);
    $subject->delete();
    Session::flash('alert-danger', 'danger');
    // Return a success response with a success message
    return redirect()->route('admin.subjects.index')->with('danger', 'Matiére supprimée !');
    }
}
