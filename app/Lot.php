<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    public function getProject() {
        return Project::where('id', $this->project)->first();
    }

    // TODO: Improve performance
    public function getSelection($design_option_id) {
        if(!BuildOut::where('lot', $this->id)->exists()) return null;

        $build_out = BuildOut::where('lot', $this->id)->first();
        foreach(explode(':', $build_out->selections) as $selection) {
            $design_option = explode('=', $selection)[0];
            $price_sheet = explode('=', $selection)[1];

            // TODO: If price sheet doesn't exist, update selections string in BuildOut table
            if($design_option == $design_option_id) {
                if(PriceSheet::where('id', $price_sheet)->exists()) {
                    return $price_sheet;
                } else {
                    return null;
                }
            }
        }

        return null;
    }
}
