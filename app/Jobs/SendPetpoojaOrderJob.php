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

    public $tries = 3;
public function backoff(): array
    {
        return [10, 30, 60]; 
    }
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    public function handle(PetpoojaService $petpoojaService): void
    {
        $petpoojaService->pushOrder($this->orderId);
    }
}