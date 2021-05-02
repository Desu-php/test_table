<?php


namespace app\models;


use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
