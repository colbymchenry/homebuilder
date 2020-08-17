<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DesignCategory extends Model
{
    public function hasOptions() {
        foreach(DesignOption::where('category', $this->id)->get() as $option) {
            if($option->hasPriceSheets()) return true;
        }
        return false;
    }
}
