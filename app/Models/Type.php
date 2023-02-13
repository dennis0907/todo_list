<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sort'
    ];

    public function todos()
    {
        return $this->hasMany('App\Models\Todo', 'type_id', 'id');
    }
}
