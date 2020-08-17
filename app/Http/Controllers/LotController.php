<?php

namespace App\Http\Controllers;

use App\HousePlan;
use Illuminate\Http\Request;
use App\Task;
use App\Lot;
use App\QuickResponse;

class LotController extends Controller
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
        $project = $request['project'];
        $lot_number = $request['number'];

        if(Lot::where('project', $project)->where('number', $lot_number)->exists()) {
            return QuickResponse::warning('That lot already exists in this project.');
        }

        $lot = new Lot();
        $lot->project = $project;
        $lot->number = $lot_number;
        $lot->save();
        
        // $default_checklist = ["Land Grade Permit (LGP)", "High Land Permit (HLP)", "Housing Permit", "Graded/Excavated", "Trench for foundation, water, and sewer",
        // "Concrete Foundation", "Exterior", "Interior", "Windows", "Fireplaces", "Rough HVAC",
        // "Rough plumbing", "Rough electrical", "Roof", "Outer Sheathing", "Exterior Siding or Stucco and paint",
        // "Drywall", "Cabinetry and millwork", "Tile, counters, moldings, and carpentry", "Doors", "Painted interior and woodwork",
        // "Plumbing fixtures", "Electrical fixtures and hardware", "Flooring", "Landscaping", "Deck"];

        // foreach($default_checklist as $key) {
        //     $task = new Task();
        //     $task->name = $request['name'];
        //     $task->relational_table = 'lots';
        //     $task->relational_id = $lot->id;
        //     $task->start_date = $request['start_date'];
        //     $task->end_date = $request['end_date'];
        //     $task->save();
        // }

        return QuickResponse::success('Lot created!', ['id' => $lot->id]);
    }

    public function delete(Request $request)
    {
        $id = $request['id'];

        if(!Lot::where('id', $id)->exists()) {
            return QuickResponse::warning('Could not find lot.');
        }
        $lot = Lot::where('id', $id)->first();
        $lot->delete();
        return QuickResponse::success('Lot deleted!');
    }

    public function index() {
        $id = \request('id');
        
        if(!Lot::where('id', $id)->exists()) {
            abort(404);
        }

        $lot = Lot::where('id', $id)->first();
        return view('lot')->with('lot', $lot)->with('project', $lot->getProject());
    }

    public function setPlan(Request $request) {
        $id = $request['id'];
        $plan_id = $request['plan_id'];

        if (!Lot::where('id', $id)->exists()) {
            return QuickResponse::error("Could not find lot.");
        }

        if ($plan_id != -1 && !HousePlan::where('id', $plan_id)->exists()) {
            return QuickResponse::warning('Could not find floor plan.');
        }

        $lot = Lot::where("id", $id)->first();
        if($plan_id < 0) {
            $lot->plan = null;
        } else {
            $lot->plan = $plan_id;
        }
        $lot->save();

        return QuickResponse::success("Plan set sucessfully.");
    }

}