<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceSheet extends Model
{
    public function getFormattedPrice() {
        $fmt = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        $fmt->setTextAttribute(\NumberFormatter::CURRENCY_CODE, 'USD');
        $fmt->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
        return  $fmt->formatCurrency($this->price, 'USD');
    }

}
