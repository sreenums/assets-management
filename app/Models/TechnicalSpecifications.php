<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalSpecifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'hardware_standard_id',
    ];

    public function hardwareStandard()
    {
        return $this->hasOne(HardwareStandard::class);
    }

}
