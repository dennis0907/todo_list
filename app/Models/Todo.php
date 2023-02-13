<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'finished',
        'todo_time',
        'user_id'
    ];

    public function getTimeleftAttribute()
    {
        $diff = Carbon::now()->diff($this->todo_time);
        return "剩餘{$diff->y}年{$diff->m}月{$diff->d}天";
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Type');
    }
}
