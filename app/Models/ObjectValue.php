<?php

namespace App\Models;

use App\Casts\Json;
use App\Models\ObjectKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
    ];

    protected $hidden = [
        'id',
        'updated_at',
        'object_key_id',
    ];

    protected function casts(): array
    {
        return [
            'value' => Json::class,
        ];
    }

    public function objectKey(): BelongsTo
    {
        return $this->belongsTo(ObjectKey::class);
    }

    public function scopeOnThisDate($query, $timestamp)
    {
        return $query->where('created_at', '<', gmdate('Y-m-d H:i:s', $timestamp))
            ->orderBy('created_at', 'desc');
    }
}
