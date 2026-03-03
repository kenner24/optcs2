<?php

namespace App\Models;

use App\Enums\ConnectorEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectorAccessToken extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "access_token",
        "refresh_token",
        "issued_at",
        "connector_type",
        "user_id",
        "quick_book_realmId",
        "access_token_expired_at",
        "refresh_token_expired_at",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'access_token_expired_at' => 'datetime',
        'refresh_token_expired_at' => 'datetime',
        'issued_at' => 'datetime',
        'connector_type' => ConnectorEnum::class,
    ];
}
