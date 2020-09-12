<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'shared_post' => 'array',
    ];


    public function sender()
    {
        // return $this->belongsTo(User::class);
        return $this->hasOne(User::class, 'id', 'sender_id');

    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'receiver_id');
    // }
}
