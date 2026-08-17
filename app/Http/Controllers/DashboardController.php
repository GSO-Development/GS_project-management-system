<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->hasRole('super_admin')) {
            return view('dashboard.super-admin');
        }

        if (\App\Models\Project::where('project_manager_id', $user->id)->exists()) {
            return view('dashboard.project-manager');
        }

        return view('dashboard.collaborator');
    }
}
