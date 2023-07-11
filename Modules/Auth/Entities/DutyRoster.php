<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class dutyRoster extends Model
{
    use HasFactory;

    protected $fillable = [
        'duration',
        'start_time',
        'end_time'
    ];

    protected $hidden = [
        'user_id'
    ];
    
    public function jobCard()
    {
        return $this->hasMany(jobCard::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return \Modules\Auth\Database\factories\DutyRosterFactory::new();
    }
}
