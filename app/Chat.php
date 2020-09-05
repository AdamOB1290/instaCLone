<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $guarded = [];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'receiver_id');
    // }
}
