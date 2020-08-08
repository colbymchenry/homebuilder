<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public function getEST() {
        $date = new \DateTime($this->created_at, new \DateTimeZone('UTC'));
        $date->setTimezone(new \DateTimeZone('America/New_York'));
        return $date->format('m/d/Y g:iA T');
    }
}
