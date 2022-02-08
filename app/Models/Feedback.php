<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    public $table = 'feedback';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'phone',
		'email',
		'message',
        'site',
	];

	// Связанные таблицы

	// Преобразование полей

	// Преобразование полей (save)
	public function setPhoneAttribute ($value)
	{
		$value = preg_replace('/[^0-9]/', '', $value);
		$this->attributes['phone'] = $value;
	}

	// Заготовки запросов

}
