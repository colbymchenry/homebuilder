<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\QuickResponse;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request){
        $task = new Task();
        $task->name = $request['name'];
        $task->relational_table = $request['relational_table'];
        $task->relational_id = $request['relational_id'];
        $task->start_date = $request['start_date'];
        $task->end_date = $request['end_date'];
        $task->save();
        return QuickResponse::success('Task created.');
    }

    public function delete(Request $request) {
        if(Task::where('id', $request['id'])->exists()) {
            $task = Task::where("id", $request['id'])->first();
            $task->delete();
            return QuickResponse::success('Task deleted');
        }

        return QuickResponse::warning('Task not found.');
    }

    public function updateStatus(Request $request) {
        $task_id = $request['id'];
        $status = $request['status'];

        if(!Task::where('id', $task_id)->exists()) {
            return QuickResponse::warning('Task not found.');
        }

        if($status !== 'completed' && $status !== 'in-progress' && $status !== 'not-started') {
            return QuickResponse::warning('Invalid status.');
        }

        $task = Task::where('id', $task_id)->first();
        $task->status = $status;
        $task->save();

        return QuickResponse::success("Status updated.");
    }

    public function index() {
        $id = request('id');

        if(!Task::where('id', $id)->exists()) {
            abort(404);
        }

        $task = Task::where('id', $id)->first();
        return view ('task')->with('task', $task);
    }

    public function indexTemplates() {
        return view('task_templates');
    }
    
}
