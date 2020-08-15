<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\QuickResponse;
use App\TaskTemplate;
use App\TemplateTask;

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

    public function createTemplate(Request $request) {
        if(TaskTemplate::where('name', $request['name'])->exists()) {
            return QuickResponse::warning('A template with that name already exists.');
        }

        $template = new TaskTemplate();
        $template->name = $request['name'];
        $template->save();

        foreach($request['tasks'] as $task) {
            $name = explode(':', $task)[0];
            $days = explode(':', $task)[1];

            $task = new TemplateTask();
            $task->name = $name;
            $task->template_id = $template->id;
            $task->alloted_days = $days;
            $task->save();
        }

        return QuickResponse::success('Task created.');
    }

    public function createTemplateTask(Request $request) {
        $template_id = $request['template_id'];
        $name = $request['name'];
        $days = $request['days'];

        if(!TaskTemplate::where('id', $template_id)->exists()) {
            return QuickResponse::warning('Template not found.');
        }

        $task = new TemplateTask();
        $task->name = $name;
        $task->template_id = $template_id;
        $task->alloted_days = $days;
        $task->save();

        return QuickResponse::success('Task created.', ['id' => $task->id]);
    }

    public function sortTemplateTask(Request $request) {
        $id = $request['id'];
        $order = $request['order'];

        if(!TemplateTask::where('id', $id)->exists()) {
            return QuickResponse::warning("Could not find task.");
        }

        $task = TemplateTask::where("id", $id)->first();
        $task->order = $order;
        $task->save();

        return QuickResponse::success("Success.");
    }

    public function deleteTemplate(Request $request) {
        if(TaskTemplate::where('id', $request['id'])->exists()) {
            $template = TaskTemplate::where("id", $request['id'])->first();
            $template->delete();
            return QuickResponse::success('Task template deleted');
        }

        return QuickResponse::warning('Task template not found.');
    }

    public function deleteTemplateTask(Request $request) {
        if(TemplateTask::where('id', $request['id'])->exists()) {
            $task = TemplateTask::where("id", $request['id'])->first();
            $task->delete();
            return QuickResponse::success('Template task deleted');
        }

        return QuickResponse::warning('Template task not found.');
    }

    public function indexTemplate() {
        $id = request('id');

        if(!TaskTemplate::where('id', $id)->exists()) {
            abort(404);
        }

        $task_template = TaskTemplate::where('id', $id)->first();
        return view ('task_template')->with('template', $task_template);
    }

    public function indexTemplates() {
        return view('task_templates');
    }
    
}
