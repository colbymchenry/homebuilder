<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\QuickResponse;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        $name = $request['name'];
        $project = new Project();

        if(Project::where('name', $name)->exists()) {
            return QuickResponse::warning('A project with that name already exist.');
        }

        $project->name = $name;
        $project->save();
        return QuickResponse::success('Project created!', ['id' => $project->id]);
    }

    public function delete(Request $request)
    {
        $id = $request['id'];

        if(!Project::where('id', $id)->exists()) {
            return QuickResponse::warning('Could not find project.');
        }
        $project = Project::where('id', $id)->first();
        $project->delete();
        return QuickResponse::success('Project deleted!');
    }

    public function index() {
        $id = \request('id');
        
        if(!Project::where('id', $id)->exists()) {
            abort(404);
        }

        $project = Project::where('id', $id)->first();
        return view('project')->with('project', $project);
    }
}
