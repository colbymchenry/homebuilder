<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceSheet extends Model
{

    public function getFormattedPrice() {
        return PriceSheet::formatToCurrency($this->price);
    }

    public static function formatToCurrency($str) { 
        return '$' . number_format($str);
    }

}