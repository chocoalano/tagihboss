<?php

namespace App\Models\LogTimelines;

use Illuminate\Database\Eloquent\Model;

class LogTimelinesModels extends Model
{
    protected $table = 'log_timelines_models';
    protected $fillable=[
    	'user_id',
    	'name',
    	'title',
    	'event',
    	'description',
        'ip_address',
        'platform',
        'is_in_apps',
        'boot',
        'device',
        'browser',
        'browser_engine',
        'agent',
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
