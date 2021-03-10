<?php

namespace App\Models\Assigments;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assigments\TaskAssigment;

class VisitJaminan extends Model
{
    // protected $connection = 'mysql_external';//offline
    protected $connection = 'mysql_external_online';//online
    protected $table = 'visit_jaminan_task_assigment';
	protected $fillable = [
		'id',
		'task_code',
		'kondisi_tempat',
		'latitude',
		'longitude',
		'file1',
		'file2',
		'file3',
		'created_at',
		'updated_at'
	];
	public function Task()
	{
		return $this->belongsTo(TaskAssigment::class, 'task_code', 'task_code');
	}
}
