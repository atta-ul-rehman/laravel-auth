<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;
    
    //  /**
    //  * Get the data type of the primary key.
    //  *
    //  * @return string
    //  */
    // public function getKeyType()
    // {
    //     return 'string'; // Assuming the primary key is a string
    // }

    
    protected $fillable = [
        'name',
    ];
    
    public function user()
    {
        return $this->hasMany(User::class);
    }
    
    public function jobCard()
    {
        return $this->hasMany(jobCard::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Auth\Database\factories\CompanyFactory::new();
    }
}
