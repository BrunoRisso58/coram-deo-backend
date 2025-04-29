<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalInstance extends Model
{
    use HasFactory;

    protected $fillable = [
        'goal_id',
        'completion_date',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}
