<?php

namespace App\Models\Assigments;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assigments\TaskAssigment;

class PaymentAssigment extends Model
{
    // protected $connection = 'mysql_external';//offline
    protected $connection = 'mysql_external_online';//online
    protected $table = 'bayar_task_assigment';
	protected $fillable = [
		'id',
		'task_code',
		'ang_ke',
		'tenor',
		'angsuran',
		'denda',
		'collect_fee',
		'titipan',
		'total_bayar',
		'sisa_angsuran',
		'sisa_denda',
		'no_bss',
		'file',
		'file_ttd_nasabah',
		'file_ttd_collection',
		'tgl_jt_tempo',
		'created_at',
		'updated_at'
	];
	public function Task()
	{
		return $this->belongsTo(TaskAssigment::class, 'task_code', 'task_code');
	}
}
