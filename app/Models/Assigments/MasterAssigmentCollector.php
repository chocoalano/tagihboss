<?php

namespace App\Models\Assigments;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assigments\Nasabah;
use App\Models\Assigments\TaskAssigment;

class MasterAssigmentCollector extends Model
{
    protected $connection = 'mysql_external_dpm';
	protected $table = 'master_all_assigment_kolektor';
	protected $fillable = [
		'id',
		'kode_kolektor',
		'NO_REKENING',
		'ft_angsuran',
		'NASABAH_ID',
		'NASABAH_ID',
		'kode_kantor',
		'kode_kolektor_old',
		'assigment_1',
		'assigment_2',
		'last_update'
	];

	public function Nasabah()
	{
		return $this->hasOne(Nasabah::class, 'NASABAH_ID', 'NASABAH_ID');
	}
	public function Task()
	{
		return $this->belongsTo(TaskAssigment::class, 'NO_REKENING', 'NO_REKENING');
	}
}
