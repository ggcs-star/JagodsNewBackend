<?php


namespace App\Http\Services;

use App\Enums\AddressType;
use App\Models\Address;

class AddressService
{
    public $data = [];
public function allAddresses()
{
    return Address::where('user_id', auth()->id())
        ->orderByDesc('is_default')
        ->latest()
        ->get();
}


    public function store($request)
{
    $previousAddress = Address::where([
        'label'   => $request->label,
        'user_id' => auth()->id()
    ])->first();

    if (!blank($previousAddress)) {
        $previousAddress->label = AddressType::OTHER;
        $previousAddress->label_name = "";
        $previousAddress->save();
    }

   
    if ($request->is_default == 1) {
        Address::where('user_id', auth()->id())
            ->update([
                'is_default' => 0
            ]);
    }

    $address = new Address;

    $address->label      = $request->label;

    $address->label_name = ($request->label == AddressType::OTHER)
        ? $request->label_name
        : trans('address_types.' . $request->label);

    $address->address    = $request->new_address;
    $address->apartment  = $request->apartment;
    $address->latitude   = $request->lat;
    $address->longitude  = $request->long;
    $address->user_id    = auth()->id();

    $address->is_default = $request->is_default ?? 0;

    $address->save();

    return $address;
}

 public function update($address, $request)
{
    
    if ($request->is_default == 1) {

        Address::where('user_id', auth()->id())
            ->where('id', '!=', $address->id)
            ->update([
                'is_default' => 0
            ]);
    }

    $address->label = $request->label;

    $address->label_name = ($request->label == AddressType::OTHER)
        ? $request->label_name
        : trans('address_types.' . $request->label);

    $address->address    = $request->new_address;
    $address->apartment  = $request->apartment;
    $address->latitude   = $request->lat;
    $address->longitude  = $request->long;
    $address->user_id    = auth()->id();

    $address->is_default = $request->is_default ?? 0;

    $address->save();

    return $address;
}



}
