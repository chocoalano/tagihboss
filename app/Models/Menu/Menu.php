<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
	protected $table = 'menus';
	protected $fillable = [
		'name',
		'type',
		'masters_id',
		'icon',
		'hrefjs',
		'link',
		'authorization',
		'list'
	];
}
