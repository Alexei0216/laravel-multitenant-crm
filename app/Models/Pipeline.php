<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PipelineStage;
use App\Models\Tenant;

class Pipeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_common',
        'tenant_id',
        'order'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function stages()
    {
        return $this->hasMany(PipelineStage::class)->orderBy('order');
    }

    public static function forUser($user)
    {
        return self::where('is_common', true)->orWhere('tenant_id', $user->tenant_id);
    }
}
