<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $string)
 * @property mixed $id
 * @property mixed $name
 * @property mixed $location
 */
class Church extends Model
{
    public $timestamps = false;

    protected $table = 'churches';

    protected $fillable = ['name', 'location'];
}
