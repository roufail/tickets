<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //
    protected $fillable = ['title','description','deadline','status','assign_id','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function assign()
    {
        return $this->belongsTo(User::class,'assign_id');
    }


}


