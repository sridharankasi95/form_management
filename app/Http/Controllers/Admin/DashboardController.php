<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Form;
use App\Models\FormSubmission;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalForms       = Form::count();
        $totalSubmissions = FormSubmission::count();

        return view('admin.dashboard', compact('totalUsers', 'totalForms', 'totalSubmissions'));
    }
}
