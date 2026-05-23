<?php

namespace App\Jobs;

use App\Models\UserDevice;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Throwable;

class ProcessDeviceLocationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public int $tries = 3;
    public int $timeout = 10;
    public int $backoff = 5;

    protected string $deviceId;


    protected string $ipAddress;

    public function __construct(string $deviceId, string $ipAddress)
    {
        $this->deviceId = $deviceId;
        $this->ipAddress = trim($ipAddress);
    }

    public function handle(): void
    {

        if (
            app()->environment('local') &&
            (
                $this->ipAddress === '127.0.0.1' ||
                $this->ipAddress === '::1' ||
                str_starts_with($this->ipAddress, '192.168.')
            )
        ) {
            $this->ipAddress = '49.36.15.20';
        }

        if (!filter_var($this->ipAddress, FILTER_VALIDATE_IP)) {

            Log::warning('GeoIP skipped due to invalid IP', [
                'device_id' => $this->deviceId,
                'ip' => $this->ipAddress,
            ]);

            return;
        }

        $device = UserDevice::find($this->deviceId);

        if (!$device) {

            Log::warning('GeoIP device not found', [
                'device_id' => $this->deviceId,
            ]);

            return;
        }

        try {


            $location = geoip($this->ipAddress);

            if (!$location) {

                Log::warning('GeoIP returned empty result', [
                    'device_id' => $this->deviceId,
                    'ip' => $this->ipAddress,
                ]);

                return;
            }

            $country = $location->country ?? null;
            $city = $location->city ?? null;


            if (
                $device->last_country === $country &&
                $device->last_city === $city
            ) {

                Log::info('GeoIP unchanged, skipping update', [
                    'device_id' => $this->deviceId,
                ]);

                return;
            }


            $device->update([
                'last_country' => $country,
                'last_city' => $city,
            ]);

            Log::info('Device GeoIP updated successfully', [
                'device_id' => $this->deviceId,
                'ip' => $this->ipAddress,
                'country' => $country,
                'city' => $city,
            ]);



            // if ($device->last_country !== $country) {
            //     dispatch(new AnalyzeSuspiciousLocationJob($device));
            // }

        } catch (Throwable $e) {

            Log::error('GeoIP processing failed', [
                'device_id' => $this->deviceId,
                'ip' => $this->ipAddress,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }


    public function failed(Throwable $exception): void
    {
        Log::critical('GeoIP job permanently failed', [
            'device_id' => $this->deviceId,
            'ip' => $this->ipAddress,
            'error' => $exception->getMessage(),
        ]);
    }
}