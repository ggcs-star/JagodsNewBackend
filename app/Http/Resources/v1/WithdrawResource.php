<?php

namespace App\Http\Resources\v1;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray( $request )
    {
        return [
            "id"                        => $this->id,
            "user"                      => $this->user->name,
            "payment_type"              => trans('payment_type.' . $this->payment_type),
            "amount"                    => $this->amount,
            'date'                      => Carbon::parse($this->date)->format('d M Y'),
        ];
    }
}
