<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ObjectKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
    ];

    protected $hidden = [
        'id',
    ];

    public function objectValues(): HasMany
    {
        return $this->hasMany(ObjectValue::class);
    }

    public function objectValuesLimited(): HasMany
    {
        return $this->objectValues()
            ->orderBy('created_at', 'desc')
            ->take(config('object.max_value_results'));
    }

    public function latestValue(): HasOne
    {
        return $this->hasOne(ObjectValue::class)->latestOfMany();
    }
}
