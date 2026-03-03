<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesForceOpportunity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'opportunity_id',
        'name',
        'source',
        'stage',
        'opportunity_created_date',
        'opportunity_close_date',
        'amount',
        'expected_revenue',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'opportunity_created_date' => 'datetime',
        'opportunity_close_date' => 'datetime',
    ];
}
