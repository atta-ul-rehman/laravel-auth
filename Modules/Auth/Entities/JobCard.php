<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class jobCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'dept_station',
        'dept_time',
        'train_num',
        'arr_station',
        'arr_time',
        'status',
        'track_time',
        'changeOver_time',
        'changeOvers'
    ];

    protected $hidden = [
        'company_id',
        'created_at',
        'updated_at',
        'user_id',
        'dutyRoster_id'
    ];

    public function dutyRoster()
    {
        return $this->belongsTo(dutyRoster::class);
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

  
    protected static function newFactory()
    {
        return \Modules\Auth\Database\factories\JobCardFactory::new();
    }
}
