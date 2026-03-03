<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesForceLead extends Model
{
    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "user_id",
        "lead_id",
        "source",
        "status",
        "annual_revenue",
        "lead_created_date",
        "lead_converted_date",
        "name"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'lead_created_date' => 'datetime',
        'lead_converted_date' => 'datetime',
    ];
}
