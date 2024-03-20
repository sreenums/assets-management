<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'hardware_standard_id',
        'technical_specification_id',
        'location_id',
        'asset_tag',
        'serial_no',
        'purchase_order',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function hardwareStandard()
    {
        return $this->belongsTo(HardwareStandard::class);
    }

    public function technicalSpecification()
    {
        return $this->belongsTo(TechnicalSpecifications::class);
    }

}
