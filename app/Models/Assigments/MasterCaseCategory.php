<?php

namespace App\Models\Assigments;

use Illuminate\Database\Eloquent\Model;

class MasterCaseCategory extends Model
{
     protected $table = 'master_case_categories';
	protected $fillable = [
		'options',
	];
}
