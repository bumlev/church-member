<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Faculty extends Model
{
    public $timestamps = false;
    protected $table = 'faculty';
    protected $fillable = ['name'];

    public function educations(): BelongsToMany
    {
        return $this->belongsToMany(Education::class, 'education_faculty');
    }
}
