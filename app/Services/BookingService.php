<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Driver;
use App\Models\Booking;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Create a booking.
     *
     * @param  \App\Models\Car $car
     * @param  \App\Models\Driver $driver
     * @param  array $data
     * @return \App\Models\Booking
     */
    public function create(Car $car, Driver $driver, array $data): Booking
    {
        try {
            DB::beginTransaction();
            $start = Carbon::parse(Arr::get($data, 'start'));
            $end = Carbon::parse(Arr::get($data, 'end'));

            if ($this->busy($car, $start, $end)) {
                abort(422, "The car is not available in that interval!");
            }

            if ($this->busy($driver, $start, $end)) {
                abort(422, "The driver is not available in that interval!");
            }

            $bookingData = [
                'car_id' => Arr::get($data, 'car'),
                'driver_id' => Arr::get($data, 'driver'),
                'start_at' => Arr::get($data, 'start'),
                'end_at' => Arr::get($data, 'end'),
            ];

            $booking = Booking::create($bookingData);
            DB::commit();
        } catch (\Exception $exp) {
            DB::rollback();

            throw $exp;
        }

        return $booking;
    }

    /**
     * Update the booking.
     *
     * @param  \App\Models\Booking $booking
     * @param  \App\Models\Car $car
     * @param  \App\Models\Driver $driver
     * @param  array $data
     * @return void
     */
    public function update(Booking $booking, Car $car, Driver $driver, array $data): void
    {
        try {
            DB::beginTransaction();
            $start = Carbon::parse(Arr::get($data, 'start'));
            $end = Carbon::parse(Arr::get($data, 'end'));

            if ($this->busy($car, $start, $end, $booking)) {
                abort(422, "The car is not available in that interval!");
            }

            if ($this->busy($driver, $start, $end, $booking)) {
                abort(422, "The driver is not available in that interval!");
            }

            $bookingData = [
                'car_id' => Arr::get($data, 'car'),
                'driver_id' => Arr::get($data, 'driver'),
                'start_at' => Arr::get($data, 'start'),
                'end_at' => Arr::get($data, 'end'),
            ];

            $booking->update($bookingData);
            DB::commit();
        } catch (\Exception $exp) {
            DB::rollback();

            throw $exp;
        }
    }

    /**
     * Delete the booking.
     *
     * @param  \App\Models\Booking $booking
     * @return void
     */
    public function delete(Booking $booking): void
    {
        $booking->delete();
    }

    /**
     * Check if model is busy.
     *
     * @param  \App\Models\Car|\App\Models\Driver $model
     * @param  \Illuminate\Support\Carbon $start
     * @param  \Illuminate\Support\Carbon $end
     * @param  \App\Models\Booking|null $booking
     * @return bool
     */
    protected function busy(Car|Driver $model, Carbon $start, Carbon $end, Booking $booking = null): bool
    {
        $busy = $model->whereHas('bookings', function($query) use ($start, $end, $booking) {
            if($booking) {
                $query->whereNot('id', $booking->id);
            }

            $query->during($start, $end);
        })->where('id', $model->id)->first();

        return !!$busy;
    }
}
