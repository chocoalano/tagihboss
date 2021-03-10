<?php

namespace App\Models\Assigments;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assigments\TaskAssigment;

class TaskAssigmentDetail extends Model
{
    // protected $connection = 'mysql_external';//offline
    protected $connection = 'mysql_external_online';//online
    protected $table = 'task_assigment_detail';
	protected $fillable = [
		'id',
		'task_code',
		'tgl_jt_tempo',
		'ang_ke',
		'ft_hari',
		'angsuran',
		'denda',
		'created_at',
		'updated_at'
	];

	public function Task()
	{
		return $this->belongsTo(TaskAssigment::class, 'task_code', 'task_code');
	}
}
