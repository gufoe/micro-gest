<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupEmail extends Model
{
    protected $guarded = ['id'];

    public static $rules = [
        'group_id' => 'required|exists:groups,id',
        'subject'  => 'required',
        'message'  => 'required',
    ];

    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    public function queue()
    {
        $job = new \App\Jobs\GroupNotifyJob($this);
        dispatch($job);
    }
}
