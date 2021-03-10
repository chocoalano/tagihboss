<?php

namespace App\Models\Assigments;

use Illuminate\Database\Eloquent\Model;

class MasterNextAction extends Model
{
    protected $table = 'master_next_actions';
	protected $fillable = [
		'options',
	];
}
