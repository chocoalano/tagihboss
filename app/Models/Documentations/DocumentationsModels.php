<?php

namespace App\Models\Documentations;

use Illuminate\Database\Eloquent\Model;

class DocumentationsModels extends Model
{
    protected $table = 'documentations_models';
    protected $fillable=[
    	'title',
    	'slug',
    	'information',
    	'attention',
    	'authorization',
    	'state',
    	'created_by_name'
    ];
}
