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
        'user_id',
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

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function assetHistories()
    {
        return $this->hasMany(AssetHistory::class);
    }
    
    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 1:
                return 'Brand New';
                break;
            case 2:
                return 'Assigned';
                break;
            case 3:
                return 'Damaged';
                break;
            default:
                return 'Unknown';
                break;
        }
    }
}
