<?php

namespace App\Http\Resources;

use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

class JsonResource extends BaseJsonResource
{
    /**
     * Convert the value to date time if not null.
     *
     * @param  mixed $value
     * @return string|null
     */
    public function toDateTime($value)
    {
        if (!is_null($value)) {
            if (!is_a($value, Carbon::class)) {
                $value = Carbon::parse($value);
            }

            return $value->format('d-m-Y h:i A');
        } else {
            return null;
        }
    }
}
