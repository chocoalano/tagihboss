<?php

namespace App\Models\Assigments;

use Illuminate\Database\Eloquent\Model;

class ViewTaskAssigment extends Model
{
	// protected $connection = 'mysql_external';//offline
    protected $connection = 'mysql_external_online';//online
    protected $table = 'view_task_assigment';
}
