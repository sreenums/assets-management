<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'action',
        'user_id',
        'description',
        'changed_fields',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

}
