<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Model;

class NotificationTo extends Model
{
    protected $table = 'notification_to';
    protected $fillable=[
    	'notification_models_id',
    	'user_id',
    	'show'
    ];
    public function notifFrom()
    {
        return $this->belongsTo('App\Models\Notification\NotificationModels', 'notification_models_id', 'id');
    }
}
