<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'action',
        'status_from',
        'status_to',
        'user_id',
        'description',
        'changed_fields',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    protected function getUpdatedAtFormattedAttribute()
    {
        $updatedAt = $this->attributes['updated_at'];
        return Carbon::parse($updatedAt)->format('d/m/Y, H:i:s');
    }

}
