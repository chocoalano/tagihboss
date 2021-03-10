<?php

namespace App\Models\Assigments;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assigments\TaskAssigment;
class ActivityAssigment extends Model
{
    // protected $connection = 'mysql_external';//offline
    protected $connection = 'mysql_external_online';//online
    protected $table = 'collections_activity_task_assignment';
	protected $fillable = [
		'id',
		'task_code',
		'kunjungan_ke',
		'bertemu',
		'karakter_debitur',
		'total_penghasilan',
		'kondisi_pekerjaan',
		'asset_debt',
		'janji_byr',
		'tgl_janji_byr',
		'case_category',
		'next_action',
		'keterangan',
		'file',
		'created_at',
		'updated_at'
	];
	public function Task()
	{
		return $this->belongsTo(TaskAssigment::class, 'task_code', 'task_code');
	}
}
