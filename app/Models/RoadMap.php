<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadMap extends Model
{
    use HasFactory;

    protected $table = 'road_maps';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'feature',
        'category',
        'status',
        'estimated_completion_date',
        'completed_date',
        'notes',
    ];
}
