<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Model;

class NotificationModels extends Model
{
    protected $table = 'notification_models';
    protected $fillable=[
    	'event',
    	'user_id',
    	'desc',
        'link_show'
    ];
    public function notifTo()
    {
        return $this->hasMany('App\Models\Notification\NotificationTo');
    }
}
