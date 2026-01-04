<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;
use App\Models\Client;
use App\Models\Activity;
use App\Models\PipelineStage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'client_id',
        'stage_id',
        'title',
        'status',
        'amount',
    ];

    protected $casts = [
        'tenant_id' => 'integer',
        'client_id' => 'integer',
        'stage_id' => 'integer',
        'amount' => 'float',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function stage()
    {
        return $this->belongsTo(PipelineStage::class, 'stage_id');
    }
}
