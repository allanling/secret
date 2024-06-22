<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObjectValue extends Model
{

    use HasFactory;

    protected $fillable = [
        'value',
    ];

    protected $hidden = [
        'id',
        'updated_at',
        'object_key_id'
    ];

    public function scopeOnThisDate($query, $timestamp)
    {
        return $query->where('created_at', gmdate("Y-m-d H:i:s", $timestamp));
    }
}