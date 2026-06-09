<?php

namespace App\Http\Resources\v1;

use Illuminate\Support\Collection;


use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PopularRestaurantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            "id"              => $this->id,
            "name"            => $this->name,
            "description"     => strip_tags($this->description),
            "address"         => $this->address,
            "image"           => $this->image,
            "avgRating"     => (float) ($this->avg_rating ?? 0),
            "avgRatingUser" => (int) ($this->total_reviews ?? 0),
            "coverImg"        =>  $this->coverImg,
            'estimated_delivery_time' => '30-40 mins',
            // 'restroType'   => 'veg',
            'restroType'   => $this->restroType,
            'is_open' => (bool) $this->is_open,
            "opening_time"    => $this->opening_time,
            "closing_time"    => $this->closing_time,
            'orders_count' => $this->total_orders ?? 0,
            // 'is_open' => false,
        ];
    }
}
