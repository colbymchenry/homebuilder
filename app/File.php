<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public function getURL() {
        return '/files/' . $this->relational_table . '_' . $this->relational_id . '_' . $this->name;
    }

}
