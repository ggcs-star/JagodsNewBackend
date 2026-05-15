<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Services\PetpoojaService;

class SendPetpoojaOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;

    // Tries: Agar API fail hui toh Laravel isko apne aap 3 baar aur try karega
    public $tries = 3; 

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    // Dependency Injection se PetpoojaService yahan mil jayegi
    public function handle(PetpoojaService $petpoojaService): void
    {
        // Service ko call karo
        $petpoojaService->pushOrder($this->orderId);
    }
}