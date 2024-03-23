<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users=User::all();
        return view('admin.users.index', ['users' => $users]);
    }

    public function create(Request $request)
    {
        Session::put('form_type', 'create');
        $rules = [
            'role' => 'required',
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
