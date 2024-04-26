<?php
namespace App\Http\Controllers\Students;
use App\Models\Form;
use App\Models\User;
use App\Models\ExamCalendar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FormCreatedNotification;


class FormController extends Controller
{


    public function index()
    {

        $forms = Form::where('student_id',auth()->user()->id)->get();
        return view('student.form.index', ['forms'=>$forms]);
    }



    public function create(Request $request)
    {

        Session::put('form_type', 'create');


        $rules = [
            'titre'=> 'required',
            'description'=> 'required',
            'file' => 'required',

        ];


        $messages= [
            'titre.required' => 'Le champ du titre est obligatoire.',
            'description.required' => 'Le champ du description est obligatoire.',

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

        $form = new Form();

        $form->titre = $request->input('titre');
        $form->description = $request->input('description');
        $form->student_id = auth()->user()->id;


        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/forms', $fileName);


        $form->file = $fileName;

        $form->save();
        Notification::send(User::where('role_id', 1)->get(), new FormCreatedNotification($form));


        Session::flash('alert-success', 'success');
        return redirect()->route('student.forms.index')->with('success', 'Formulaire ajoutée avec succées !');

    }



    public function update(Request $request,$id)
    {
        Session::put('form_type', 'edit');
        session()->put('edit_form_id', $id);

        $form = Form::findOrFail($id);

        $rules = [
            'titre'=> 'required',
            'description'=> 'required',

        ];


        $messages= [
            'titre.required' => 'Le champ du titre est obligatoire.',
            'description.required' => 'Le champ du description est obligatoire.',


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

        $form->titre = $request->input('titre');
        $form->description = $request->input('description');
        if($file = $request->file('fileEdit')){

            $file = $request->file('fileEdit');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/forms', $fileName);
            $form->file = $fileName;
        }


        $form->save();

        Session::flash('alert-success', 'success');
        return redirect()->route('student.forms.index')->with('warning', 'Formulaire modifiée avec succées !');
    }

    public function destroy($id)
    {
    $form = Form::findOrFail($id);
    $form->delete();
    Session::flash('alert-danger', 'danger');
    // Return a success response with a success message
    return redirect()->route('student.forms.index')->with('danger', 'Formulaire suprimée avec succées !');
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
