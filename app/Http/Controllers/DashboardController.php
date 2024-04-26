<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Field;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $news = News::latest()->get();
        $Nbteachers=User::where('role_id','3')->count();
        $Nbstudents=User::where('role_id','2')->count();
        $Nbdep=Department::count();
        $Nbfields=Field::count();
        return view('dashboard', ['news' => $news,'Nbteachers'=>$Nbteachers,'Nbstudents'=>$Nbstudents,'Nbdep'=>$Nbdep,'Nbfields'=>$Nbfields]);
    }

    public function indexNew($id)
    {
        $new = News::where('id', $id)->first();
        return view('news', ['new' => $new]);
    }
}
