<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uid',
        'status',
        'price',
        'used_blocks',
        'num_of_days'
    ];


    protected $appends = [
        'ends_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * A Order belongs to user.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A Order belongs to location.
     *
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function getEndsAtAttribute()
    {
        return (new Carbon($this->arributes['created_at']))->addDays($this->arributes['num_of_days'])->format('Y-m-d h:i:s');
    }

 
    public function getUsedBlocksAttribute()
    {
        return collect(json_decode($this->attributes['used_blocks'], true)) ?? [];
    }

    /**
     * @param $value
     * @return $this
     */
    public function setUsedBlocksAttribute($value)
    {
        $this->attributes['used_blocks'] = json_encode($value);
        return $this;
    }
}

