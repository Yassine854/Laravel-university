<?php

namespace App\Http\Controllers\Admin;

use App\Models\Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::with('student.field')->get();
        return view('admin.forms.index', ['forms' => $forms]);
    }




    public function update(Request $request,$id)
    {
        Session::put('form_type', 'edit');
        session()->put('edit_form_id', $id);

        $form = Form::findOrFail($id);

        $rules = [
            'state'=> 'required',

        ];


        $messages= [
            'state.required' => 'Le champ état est obligatoire.',


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

        $form->state = $request->input('state');

        $form->save();

        Session::flash('alert-success', 'success');
        return redirect()->route('admin.forms.index')->with('warning', 'Formulaire modifiée avec succées !');
    }




    public function download($fileName)
    {
        // Get the file path
        $filePath = storage_path('app/public/forms/' . $fileName);

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
