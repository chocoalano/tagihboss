<?php

namespace App\Models\Informations;

use Illuminate\Database\Eloquent\Model;

class InfocollModels extends Model
{
	protected $table = 'infocoll_models';
    protected $fillable=[
    	'title',
    	'slug',
    	'information',
    	'state'
    ];
}
