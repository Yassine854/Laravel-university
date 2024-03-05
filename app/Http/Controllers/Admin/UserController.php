<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    public function index()
    {
        $users=User::all();
        return view('admin.users.index', ['users' => $users]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'role' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = new User;
        $user->name = $request->input('name');
        $user->role_id = $request->input('role');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        $users = User::all();
        return view('admin.users.index', ['users' => $users])->with('success', 'User created successfully');
    }


    public function edit($id)
    {
        $user = User::find($id);
        return view('student.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'sometimes|string|min:8',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->role_id = $request->input('role');
        $user->email = $request->input('email');
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    // Return a success response with a success message
    return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
}

}
