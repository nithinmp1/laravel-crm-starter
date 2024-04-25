<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use VentureDrake\LaravelEncryptable\Traits\LaravelEncryptableTrait;

class Qualification extends Model
{
    use HasFactory, SoftDeletes;
    use LaravelEncryptableTrait;
    
    protected $guarded = ['id'];

    protected $encryptable = [
        'name',
        'percentage',
        'institute',
        'univercity',
        'location',
    ];

    protected $fillable = [
        'name',
        'percentage',
        'institute',
        'univercity',
        'location',
        'start_at',
        'finish_at',
        'user_owner_id',
        'user_assigned_id',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'finish_at' => 'datetime',
    ];

    protected $searchable = [
        'name',
        'description',
    ];

    public function getSearchable()
    {
        return $this->searchable;
    }

    public function getNameDecryptedAttribute()
    {
        return $this->decryptField($this->name);
    }
    /**
     * Get all of the owning callable models.
     */
    public function lunchable()
    {
        return $this->morphTo('lunchable');
    }

    public function createdByUser()
    {
        return $this->belongsTo(\App\User::class, 'user_created_id');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(\App\User::class, 'user_updated_id');
    }

    public function deletedByUser()
    {
        return $this->belongsTo(\App\User::class, 'user_deleted_id');
    }

    public function restoredByUser()
    {
        return $this->belongsTo(\App\User::class, 'user_restored_id');
    }

    public function ownerUser()
    {
        return $this->belongsTo(\App\User::class, 'user_owner_id');
    }

    public function assignedToUser()
    {
        return $this->belongsTo(\App\User::class, 'user_assigned_id');
    }

    public function activity()
    {
        return $this->morphOne(\VentureDrake\LaravelCrm\Models\Activity::class, 'recordable');
    }

    public function setStartAtAttribute($value)
    {
        if ($value) {
            $this->attributes['start_at'] = Carbon::createFromFormat($this->dateFormat().' H:i', $value);
        }
    }

    public function setFinishAtAttribute($value)
    {
        if ($value) {
            $this->attributes['finish_at'] = Carbon::createFromFormat($this->dateFormat().' H:i', $value);
        }
    }
}
