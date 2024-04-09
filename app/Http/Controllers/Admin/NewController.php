<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class NewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $news = News::all();
    return view('admin.news.index', compact('news'));
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
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',

        ];

        $messages= [
            'title.required' => 'Le champ titre est obligatoire.',
            'description.required' => 'Le champ description est obligatoire.',
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



        $new = new News();
        $new->title = $request->input('title');
        $new->description = $request->input('description');
        $new->save();
        Session::flash('alert-success', 'success');
        return redirect()->route('admin.news.index')->with('success', 'Actualité ajouté avec succées !');
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
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Session::put('form_type', 'edit');
        session()->put('edit_new_id', $id);
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',

        ];

        $messages= [
            'title.required' => 'Le champ titre est obligatoire.',
            'description.required' => 'Le champ description est obligatoire.',
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

        $new = News::findOrFail($id);
        $new->title = $request->input('title');
        $new->description = $request->input('description');

        $new->save();
        Session::flash('alert-warning', 'warning');

        return redirect()->route('admin.news.index')->with('warning', 'Actualité modifié avec succées !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
    $new = News::findOrFail($id);
    $new->delete();
    Session::flash('alert-danger', 'danger');
    // Return a success response with a success message
    return redirect()->route('admin.news.index')->with('danger', 'Actualité supprimé !');
    }
}
