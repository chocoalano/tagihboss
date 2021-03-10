<?php

namespace App\Models\Assigments;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assigments\TaskAssigmentDetail;
use App\Models\Assigments\MasterAssigmentCollector;
use App\Models\Assigments\VisitTempatTinggal;
use App\Models\Assigments\VisitJaminan;
use App\Models\Assigments\ActivityAssigment;
use App\Models\Assigments\KreNominatif;

class TaskAssigment extends Model
{
	// protected $connection = 'mysql_external';//offline
    protected $connection = 'mysql_external_online';//online
    protected $table = 'task_assigment';
	protected $fillable = [
		'id',
		'task_code',
		'no_rekening',
		'kode_group3',
		'kode_kantor',
		'os_pokok',
		'assignment_date',
		'collect_fee',
		'total_tagihan',
		'flag_aktif',
		'created_at',
		'updated_at'
	];
	public function Master()
	{
		return $this->hasOne(MasterAssigmentCollector::class, 'NO_REKENING', 'no_rekening');
	}
	public function Detail()
	{
		return $this->hasOne(TaskAssigmentDetail::class, 'task_code', 'task_code');
	}
	public function VisitTT()
	{
		return $this->hasOne(VisitTempatTinggal::class, 'task_code', 'task_code');
	}
	public function VisitJM()
	{
		return $this->hasOne(VisitJaminan::class, 'task_code', 'task_code');
	}
	public function Activity()
	{
		return $this->hasOne(ActivityAssigment::class, 'task_code', 'task_code');
	}
	public function Task()
	{
		return $this->hasOne(KreNominatif::class, 'no_rekening', 'no_rekening');
	}
}
