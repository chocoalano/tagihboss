<?php

namespace App\Models\Assigments;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assigments\MasterAssigmentCollector;

class Nasabah extends Model
{
	// protected $connection = 'mysql_external_dpm';//offline
    protected $connection = 'mysql_external_dpm_online';//online
	protected $table = 'nasabah';
	protected $fillable = [
		'NASABAH_ID',
		'NAMA_NASABAH',
		'ALAMAT',
		'TELEPON',
		'JENIS_KELAMIN',
		'TEMPATLAHIR',
		'TGLLAHIR',
		'NO_ID'
	];

	public function Task()
	{
		return $this->belongsTo(MasterAssigmentCollector::class, 'NASABAH_ID', 'NASABAH_ID');
	}
}
