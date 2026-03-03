<?php

namespace App\Models;

use App\Enums\ConnectorEnum;
use App\Enums\UserStatusEnum;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity;

    protected static $ignoreChangedAttributes = ['password', 'updated_at'];
    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'image',
        'status',
        'company_id',
        'company_name',
        'work_email',
        'total_employees',
        'domain_sector',
        'username',
        'slug',
        'email_token',
        'provider_name',
        'provider_id',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_token',
        'connectors',
        'permissions'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => UserStatusEnum::class
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['sales_force_access', 'google_sheet_access', 'quick_books_access', 'assigned_permissions'];

    // ================================================Relations Start=======================================
    /**
     * Get the connectors list
     */
    public function connectors(): HasMany
    {
        return $this->hasMany(ConnectorAccessToken::class);
    }

    /**
     * Get the staff company detail
     */
    public function staffCompanyDetails(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id', 'id')
        ->select("id", "company_id", "company_name", "total_employees", "domain_sector");
    }

    /**
     * Get the all the members of the company staff
     */
    public function companyStaff(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'company_id');
    }

    // ================================================Relations End=======================================

    // ===================================================virtual attributes===============================
    /**
     * Determine if the user has salesforce access or not.
     */
    protected function salesForceAccess(): Attribute
    {
        $result = Arr::where($this->connectors->toArray(), function ($value, $key) {
            return $value["connector_type"] === ConnectorEnum::SALESFORCE->value;
        });

        return new Attribute(
            get: fn () => count($result) > 0 ? true : false,
        );
    }

    /**
     * Determine if the user has quickbooks access or not.
     */
    protected function quickBooksAccess(): Attribute
    {
        $result = Arr::where($this->connectors->toArray(), function ($value, $key) {
            return $value["connector_type"] === ConnectorEnum::QUICKBOOKS->value;
        });

        return new Attribute(
            get: fn () => count($result) > 0 ? true : false,
        );
    }

    /**
     * Determine if the user has googlesheet access or not access or not.
     */
    protected function googleSheetAccess(): Attribute
    {
        $result = Arr::where($this->connectors->toArray(), function ($value, $key) {
            return $value["connector_type"] === ConnectorEnum::GOOGLESHEET->value;
        });

        return new Attribute(
            get: fn () => count($result) > 0 ? true : false,
        );
    }

    /**
     * Return list of all the assigned permission
     */
    protected function assignedPermissions(): Attribute
    {
        return new Attribute(
            get: fn () => $this->getAllPermissions()->pluck('name')->all(),
        );
    }

    // ===================================================virtual attributes end===============================

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    // ================================================ Mutators Start ==============================================
    /**
     * Get the user's image.
     *
     * @param  string  $value
     * @return string
     */
    public function getImageAttribute($value)
    {
        return isset($value) ? asset($value) : asset('/dummy_images/dummy.jpeg');
    }

    // ================================================ Mutators End ================================================
}
