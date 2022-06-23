<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Block extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'length',
        'width',
        'height',
        'empty'
    ];

    protected $appends = [
        'volume'
    ];

    /**
     * A Block belongs to freezingRoom.
     *
     * @return BelongsTo
     */
    public function freezingRoom(): BelongsTo
    {
        return $this->belongsTo(FreezingRoom::class);
    }

    public function getVolumeAttribute()
    {
        return round($this->attributes['length'] * $this->attributes['width'] * $this->attributes['height']);
    }


}
