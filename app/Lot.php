<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    public function getProject() {
        return Project::where('id', $this->project)->first();
    }
}
