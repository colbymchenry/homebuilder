<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\QuickResponse;
use App\TaskTemplate;
use App\TemplateTask;
use PayPal\Api\Template;

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

    public function deleteTemplate(Request $request) {
        if(TaskTemplate::where('id', $request['id'])->exists()) {
            $template = TaskTemplate::where("id", $request['id'])->first();
            $template->delete();
            return QuickResponse::success('Task template deleted');
        }

        TemplateTask::where('template_id', $request['id'])->delete();

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

    public function renameTemplate(Request $request) {
        $id = $request['id'];

        if(!TaskTemplate::where('id', $id)->exists()) {
            abort(404);
        }

        $task_template = TaskTemplate::where('id', $id)->first();
        $task_template->name = $request['name'];
        $task_template->save();

        return QuickResponse::success("Success");
    }

    public function saveTemplate(Request $request) {
        $name_days = $request['namesAndDays'];        
        foreach($request['order'] as $order => $task_id) {
            if(TemplateTask::where('id', $task_id)->exists()) {
                $task = TemplateTask::where('id', $task_id)->first();
                $task->order = $order;
                if(array_key_exists($task_id, $name_days)) {
                    $name = explode(':', $name_days[$task_id])[0];
                    $days = explode(':', $name_days[$task_id])[1];
                    $task->name = $name;
                    $task->alloted_days = $days;
                }
                $task->save();
            }
        }
        return QuickResponse::success("Success");
    }

    public function loadTemplate(Request $request) {
        $lot_id = $request['lot_id'];
        $template_id = $request['template_id'];

        Task::where('relational_table', 'lots')->where('relational_id', $lot_id)->delete();

        foreach(TemplateTask::where('template_id', $template_id)->get() as $template_task) {
            $task = new Task();
            $task->name = $template_task->name;
            $task->relational_table = 'lots';
            $task->relational_id = $lot_id;
            $Date = date("Y-m-d");
            $task->start_date = $Date;
            $task->end_date = date('Y-m-d', strtotime($Date. ' + ' . $template_task->alloted_days . ' days'));
            $task->save();
        }

        return QuickResponse::success('Success');
    }
    
}
