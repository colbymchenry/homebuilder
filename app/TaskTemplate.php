<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskTemplate extends Model
{
    public function getTasks() {
        return TemplateTask::where('template_id', $this->id)->get();
    }
}
