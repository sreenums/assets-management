<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
    ];

    public function hardwareStandard()
    {
        return $this->hasMany(HardwareStandard::class);
    }

    public function technicalSpecifications()
    {
        return $this->hasManyThrough(TechnicalSpecifications::class, HardwareStandard::class);
    }

}
