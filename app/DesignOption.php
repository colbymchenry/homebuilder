<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DesignOption extends Model
{

    public function hasPriceSheets() {
        return PriceSheet::where('design_option', $this->id)->exists();
    }

}
