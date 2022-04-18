<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Booking;
use App\Services\BookingService;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BookingResource;
use App\Http\Requests\V1\Booking\CreateOrUpdateBookingRequest;

class BookingController extends Controller
{
    use Traits\FindCarById;
    use Traits\FindDriverById;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = Booking::paginate(10);

        return response(BookingResource::collection($bookings));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\V1\Booking\CreateOrUpdateBookingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrUpdateBookingRequest $request)
    {
        $car = $this->findCarById($request->get('car'));
        $driver = $this->findDriverById($request->get('driver'));

        $booking = resolve(BookingService::class)->create($car, $driver, $request->validated());

        return response(BookingResource::make($booking));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        return response(BookingResource::make($booking));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\V1\Booking\UpdateBookingRequest $request
     * @param  \App\Models\Booking $booking
     * @return \Illuminate\Http\Response
     */
    public function update(CreateOrUpdateBookingRequest $request, Booking $booking)
    {
        $car = $this->findCarById($request->get('car'));
        $driver = $this->findDriverById($request->get('driver'));

        resolve(BookingService::class)->update($booking, $car, $driver, $request->validated());

        return response(BookingResource::make($booking));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        resolve(BookingService::class)->delete($booking);
    }
}
