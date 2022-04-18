<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bookings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'car_id',
        'driver_id',
        'start_at',
        'end_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    /**
     * Get the car associated with the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function car(): HasOne
    {
        return $this->hasOne(Car::class, 'id', 'car_id');
    }

    /**
     * Get the driver associated with the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }

    /**
     * Filter by dates.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Carbon\Carbon $start
     * @param  \Carbon\Carbon $end
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDuring($query, Carbon $start, Carbon $end)
    {
        return $query->where(function ($scopedQuery) use ($start, $end) {
            $scopedQuery->where('start_at', '>=', $start)
                ->where('start_at', '<=', $end)
                ->orWhere('end_at', '>=', $start)
                ->where('end_at', '<=', $end)
                ->orWhere('start_at', '<=', $start)
                ->where('end_at', '>=', $end);
        });
    }
}
