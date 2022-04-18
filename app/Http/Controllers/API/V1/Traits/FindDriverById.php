<?php

namespace App\Http\Controllers\API\V1\Traits;

use App\Models\Driver;

trait FindDriverById
{
    /**
     * Get driver by ID.
     *
     * @param  int|string $id
     * @return \App\Models\Driver
     */
    protected function findDriverById(int|string $id): Driver
    {
        $driver = Driver::find($id);

        if (!$driver) {
            abort(404, 'The driver is not found');
        }

        return $driver;
    }
}
