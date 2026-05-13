<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ShowCartAppOnly extends Component
{
   public function redirectToCart()
{
    return redirect()->to(url('/cart-app'));
}

}
