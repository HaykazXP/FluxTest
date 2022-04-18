<?php

namespace App\Http\Controllers\API\V1\Traits;

use App\Models\Car;

trait FindCarById
{
    /**
     * Get car by ID.
     *
     * @param  int|string $id
     * @return \App\Models\Car
     */
    protected function findCarById(int|string $id): Car
    {
        $car = Car::find($id);

        if (!$car) {
            abort(404, 'The car is not found');
        }

        return $car;
    }
}
