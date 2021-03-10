<?php

namespace App\Models\Assigments;

use Illuminate\Database\Eloquent\Model;

class MasterKondisiPekerjaan extends Model
{
    protected $table = 'master_kondisi_pekerjaans';
	protected $fillable = [
		'options',
	];
}
