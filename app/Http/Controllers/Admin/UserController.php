<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Field;
use App\Models\Subject;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    //Admin

    public function indexAdmin()
    {
        $departments=Department::all();
        $fields=Field::all();
        $users = User::with('field')->where('role_id', 1)->get();

        return view('admin.users.admins', ['users' => $users,'fields'=>$fields,'departments'=>$departments]);
    }

//For student interface
public function indexGroupList()
{
    $students = User::with('field')
                    ->where('role_id', 2)
                    ->where('groupe', auth()->user()->groupe)
                    ->orderBy('last_name')
                    ->get();

    return view('student.groupes.index', ['students' => $students]);
}

//

    public function createAdmin(Request $request)
    {
        Session::put('form_type', 'create');
        $rules = [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ];

        $messages= [
            'name.required' => 'Le champ prénom est obligatoire.',
            'last_name.required' => 'Le champ nom est obligatoire.',
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'max' => [
                'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            ],
            'email' => 'Le champ :attribute doit être une adresse email valide.',
            'unique' => 'La valeur du champ :attribute est déjà utilisée.',
            'min' => [
                'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
            ],
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with(['operation' => 'create']);
        }


        $user = new User;
        $user->role_id = 1;
        $user->name = $request->input('name');
        $user->last_name= $request->input('last_name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();
        Session::flash('alert-success', 'success');
        return redirect()->route('admin.users.indexAdmin')->with('success', 'Admin ajouté avec succées !');
    }

    public function updateAdmin(Request $request, $id)
    {
        Session::put('form_type', 'edit');
        session()->put('edit_user_id', $id);
        $rules = [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'nullable',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id),
            ],

        ];

        $messages= [
            'name.required' => 'Le champ prénom est obligatoire.',
            'last_name.required' => 'Le champ nom est obligatoire.',
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'max' => [
                'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            ],
            'email' => 'Le champ :attribute doit être une adresse email valide.',
            'unique' => 'La valeur du champ :attribute est déjà utilisée.',
            'min' => [
                'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
            ],
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }




        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->save();
        Session::flash('alert-warning', 'warning');

        return redirect()->route('admin.users.indexAdmin')->with('warning', 'Admin modifié avec succées !');
    }

    public function destroyAdmin($id)
{
    $user = User::findOrFail($id);
    $user->delete();
    Session::flash('alert-danger', 'danger');
    // Return a success response with a success message
    return redirect()->route('admin.users.indexAdmin')->with('danger', 'Admin supprimé !');
}

    //Enseignant

public function indexTeacher()
{
    $departments=Department::all();
    $fields=Field::all();
    $users = User::with('field')->where('role_id', 3)->get();
    $allSubjects=Subject::all();
    $subjects = Subject::whereDoesntHave('users', function ($query) {
        $query->where('role_id', 3);
    })->get();
    return view('admin.users.teachers', ['users' => $users,'fields'=>$fields,'departments'=>$departments,'allSubjects'=>$allSubjects,'subjects'=>$subjects]);
}


public function createTeacher(Request $request)
{
    Session::put('form_type', 'create');
    $rules = [
        'name' => 'required|string|max:255',
        'subjects'=> 'required',
        'last_name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'phone' => 'required|string|size:8',
        'Nbureau' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
    ];

    $messages = [
        'name.required' => 'Le champ prénom est obligatoire.',
        'subjects.required' => 'Le champ matiéres est obligatoire.',
        'last_name.required' => 'Le champ nom est obligatoire.',
        'address.required' => 'Le champ adresse est obligatoire.',
        'phone.required' => 'Le champ Téléphone est obligatoire.',
        'phone.size' => 'Le champ Téléphone doit avoir une longueur de :size chiffres.',
        'Nbureau.required' => 'Le champ Numéro de bureau est obligatoire.',
        'required' => 'Le champ :attribute est obligatoire.',
        'string' => 'Le champ :attribute doit être une chaîne de caractères.',
        'max' => [
            'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
        ],
        'email' => 'Le champ :attribute doit être une adresse email valide.',
        'unique' => 'La valeur du champ :attribute est déjà utilisée.',
        'min' => [
            'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
        ],
    ];


    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->withInput()
            ->with(['operation' => 'create']);
    }


    $user = new User;
    $user->role_id = 3;
    $user->name = $request->input('name');
    $user->last_name= $request->input('last_name');
    $user->address= $request->input('address');
    $user->phone= $request->input('phone');
    $user->Nbureau= $request->input('Nbureau');
    $user->email = $request->input('email');
    $user->password = bcrypt($request->input('password'));
    $user->save();
    if ($request->has('subjects')) {
        $user->subjects()->attach($request->input('subjects'));
    }
    Session::flash('alert-success', 'success');
    return redirect()->route('admin.users.indexTeacher')->with('success', 'Enseignant ajouté avec succées !');
}

public function updateTeacher(Request $request, $id)
{
    Session::put('form_type', 'edit');
    session()->put('edit_user_id', $id);
    $rules = [
        'name' => 'required|string|max:255',
        'subjects' => 'required',
        'last_name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'phone' => 'required|string|size:8',
        'Nbureau' => 'required|string|max:255',
        'email' => [
            'required',
            'nullable',
            'email',
            'max:255',
            Rule::unique('users')->ignore($id),
        ],

    ];

    $messages = [
        'name.required' => 'Le champ prénom est obligatoire.',
        'subjects.required' => 'Le champ matiéres est obligatoire.',
        'last_name.required' => 'Le champ nom est obligatoire.',
        'address.required' => 'Le champ adresse est obligatoire.',
        'phone.required' => 'Le champ Téléphone est obligatoire.',
        'phone.size' => 'Le champ Téléphone doit avoir une longueur de :size chiffres.',
        'Nbureau.required' => 'Le champ Numéro de bureau est obligatoire.',
        'required' => 'Le champ :attribute est obligatoire.',
        'string' => 'Le champ :attribute doit être une chaîne de caractères.',
        'max' => [
            'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
        ],
        'email' => 'Le champ :attribute doit être une adresse email valide.',
        'unique' => 'La valeur du champ :attribute est déjà utilisée.',
        'min' => [
            'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
        ],
    ];


    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->withInput();
    }




    $user = User::findOrFail($id);

    $user->name = $request->input('name');
    $user->last_name = $request->input('last_name');
    $user->address= $request->input('address');
    $user->phone= $request->input('phone');
    $user->Nbureau= $request->input('Nbureau');
    $user->email = $request->input('email');
    if ($request->filled('password')) {
        $user->password = bcrypt($request->input('password'));
    }
    $user->save();

    if ($request->filled('subjects')) {
        $user->subjects()->sync($request->input('subjects'));
    } else {
        // If no subjects provided, detach all subjects
        $user->subjects()->detach();
    }
    Session::flash('alert-warning', 'warning');

    return redirect()->route('admin.users.indexTeacher')->with('warning', 'Enseignant modifié avec succées !');
}

public function destroyTeacher($id)
{
$user = User::findOrFail($id);
$user->delete();
Session::flash('alert-danger', 'danger');
// Return a success response with a success message
return redirect()->route('admin.users.indexTeacher')->with('danger', 'Enseignant supprimé !');
}



    //Etudiant

    public function indexStudent()
    {
        $departments=Department::all();
        $fields=Field::all();
        $users = User::with('field')->where('role_id', 2)->get();

        return view('admin.users.students', ['users' => $users,'fields'=>$fields,'departments'=>$departments]);
    }


    public function createStudent(Request $request)
    {
        Session::put('form_type', 'create');
        $rules = [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|size:8',
            'field_id' => 'required',
            'groupe' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ];

        $messages = [
            'name.required' => 'Le champ prénom est obligatoire.',
            'last_name.required' => 'Le champ nom est obligatoire.',
            'address.required' => 'Le champ adresse est obligatoire.',
            'phone.required' => 'Le champ Téléphone est obligatoire.',
            'phone.size' => 'Le champ Téléphone doit avoir une longueur de :size chiffres.',
            'field_id.required' => 'Le champ Filiére est obligatoire.',
            'groupe.required' => 'Le champ groupe est obligatoire.',
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'max' => [
                'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            ],
            'email' => 'Le champ :attribute doit être une adresse email valide.',
            'unique' => 'La valeur du champ :attribute est déjà utilisée.',
            'min' => [
                'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
            ],
        ];


        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with(['operation' => 'create']);
        }


        $user = new User;
        $user->role_id = 2;
        $user->name = $request->input('name');
        $user->last_name= $request->input('last_name');
        $user->address= $request->input('address');
        $user->phone= $request->input('phone');
        $user->field_id= $request->input('field_id');
        $user->groupe= $request->input('groupe');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();
        Session::flash('alert-success', 'success');
        return redirect()->route('admin.users.indexStudent')->with('success', 'Enseignant ajouté avec succées !');
    }

    public function updateStudent(Request $request, $id)
    {
        Session::put('form_type', 'edit');
        session()->put('edit_user_id', $id);


        $rules = [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|size:8',
            'field_id' => 'required',
            'groupe' => 'required',
            'email' => [
                'required',
                'nullable',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id),
            ],
        ];

        $messages = [
            'name.required' => 'Le champ prénom est obligatoire.',
            'last_name.required' => 'Le champ nom est obligatoire.',
            'address.required' => 'Le champ adresse est obligatoire.',
            'phone.required' => 'Le champ Téléphone est obligatoire.',
            'phone.size' => 'Le champ Téléphone doit avoir une longueur de :size chiffres.',
            'field_id.required' => 'Le champ Filiére est obligatoire.',
            'groupe.required' => 'Le champ groupe est obligatoire.',
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'max' => [
                'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            ],
            'email' => 'Le champ :attribute doit être une adresse email valide.',
            'unique' => 'La valeur du champ :attribute est déjà utilisée.',
            'min' => [
                'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
            ],
        ];


        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }




        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->last_name = $request->input('last_name');
        $user->address= $request->input('address');
        $user->phone= $request->input('phone');
        $user->field_id= $request->input('field_id');
        $user->groupe= $request->input('groupe');
        $user->email = $request->input('email');
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();
        Session::flash('alert-warning', 'warning');

        return redirect()->route('admin.users.indexStudent')->with('warning', 'Etudiant modifié avec succées !');
    }

    public function destroyStudent($id)
    {
    $user = User::findOrFail($id);
    $user->delete();
    Session::flash('alert-danger', 'danger');
    // Return a success response with a success message
    return redirect()->route('admin.users.indexStudent')->with('danger', 'Etudiant supprimé !');
    }















    public function create(Request $request)
    {
        Session::put('form_type', 'create');
        $rules = [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ];

        $messages= [
            'name.required' => 'Le champ prénom est obligatoire.',
            'last_name.required' => 'Le champ nom est obligatoire.',
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'max' => [
                'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            ],
            'email' => 'Le champ :attribute doit être une adresse email valide.',
            'unique' => 'La valeur du champ :attribute est déjà utilisée.',
            'min' => [
                'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
            ],
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with(['operation' => 'create']);
        }


        $user = new User;
        $user->role_id = $request->input('role');
        $user->name = $request->input('name');
        $user->last_name= $request->input('last_name');
        $user->field_id= $request->input('field_id');
        $user->groupe= $request->input('groupe');
        $user->address= $request->input('address');
        $user->phone= $request->input('phone');
        $user->Nbureau= $request->input('Nbureau');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();
        Session::flash('alert-success', 'success');
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur ajouté avec succées !');
    }

    // public function edit($id)
    // {
    //     $user = User::find($id);
    //     return view('student.edit', compact('student'));
    // }

    public function update(Request $request, $id)
    {
        Session::put('form_type', 'edit');
        session()->put('edit_user_id', $id);
        $rules = [
            'role' => 'required',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'nullable',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id),
            ],

        ];

        $messages= [
            'name.required' => 'Le champ prénom est obligatoire.',
            'last_name.required' => 'Le champ nom est obligatoire.',
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'max' => [
                'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            ],
            'email' => 'Le champ :attribute doit être une adresse email valide.',
            'unique' => 'La valeur du champ :attribute est déjà utilisée.',
            'min' => [
                'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
            ],
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }




        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->last_name = $request->input('last_name');
        $user->address = $request->input('address');
        $user->phone = $request->input('phone');
        $user->Nbureau = $request->input('Nbureau');
        $user->role_id = $request->input('role');
        $user->email = $request->input('email');
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();
        Session::flash('alert-warning', 'warning');

        return redirect()->route('admin.users.index')->with('warning', 'Utilisateur modifié avec succées !');
    }

    public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();
    Session::flash('alert-danger', 'danger');
    // Return a success response with a success message
    return redirect()->route('admin.users.index')->with('danger', 'Utilisateur supprimé !');
}




}
