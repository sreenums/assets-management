<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HardwareStandard extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'type_id',
    ];

    public function technicalSpecification()
    {
        return $this->hasMany(TechnicalSpecifications::class);
    }
    
}
