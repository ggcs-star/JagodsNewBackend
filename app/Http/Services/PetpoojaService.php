<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PetpoojaService
{
    public function pushOrder(int $orderId)
    {
        try {
            Log::info('Sending order to Petpooja', ['order_id' => $orderId]);

            $response = Http::withHeaders([
                'X-API-KEY' => env('PETPOOJA_API_KEY', 'xyz1'),
                'Accept' => 'application/json',
            ])
                ->timeout(5) 
                ->retry(2, 100) 
                ->get('http://petpooja.jagods.com/public/api/petpooja/order-payload?order_id=' . $orderId);

            Log::info('Petpooja API response', [
                'order_id' => $orderId,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                Log::error('Order push failed', [
                    'order_id' => $orderId,
                    'response' => $response->body(),
                ]);

                throw new Exception("Petpooja API returned " . $response->status());
            }

            return true;

        } catch (Exception $e) {
            Log::error('Order push exception', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}