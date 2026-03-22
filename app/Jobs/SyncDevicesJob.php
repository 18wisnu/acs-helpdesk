<?php

namespace App\Jobs;

use App\Models\Device;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncDevicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah retry jika job gagal.
     */
    public int $tries = 3;

    /**
     * Timeout per eksekusi (detik).
     */
    public int $timeout = 60;

    /**
     * Jalankan sinkronisasi device dari GenieACS ke database lokal.
     */
    public function handle(): void
    {
        $genieAcsUrl = config('services.genieacs.url');

        $projection = implode(',', [
            'VirtualParameters',
            'InternetGatewayDevice.DeviceInfo.ModelName',
            'InternetGatewayDevice.DeviceInfo.SerialNumber',
            'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSID',
            'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.ExternalIPAddress',
        ]);

        $response = Http::timeout(30)->get("{$genieAcsUrl}/devices/?projection={$projection}");

        if (! $response->successful()) {
            Log::warning('[SyncDevicesJob] GenieACS tidak merespons. Status: ' . $response->status());
            return;
        }

        $devices = $response->json();
        $count   = 0;

        foreach ($devices as $device) {
            $ssid      = $this->getVP($device, 'WlanSSID',
                            data_get($device, 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSID._value', '-'));
            $ipPppoe   = $this->getVPFirst($device, ['pppoeIP', 'IPPPPOE'],
                            data_get($device, 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.ExternalIPAddress._value', '-'));
            $ipTr069   = $this->getVP($device, 'IPTR069');
            $sn        = $this->getVPFirst($device, ['getSerialNumber', 'SerialNumber', 'SN'],
                            data_get($device, 'InternetGatewayDevice.DeviceInfo.SerialNumber._value', '-'));
            $brand     = $this->getVP($device, 'Model',
                            data_get($device, 'InternetGatewayDevice.DeviceInfo.ModelName._value', 'Unknown'));
            $activeDev = $this->getVPFirst($device, ['activedevices', 'ActiveDevices'], '0');
            $uptime    = $this->getVPFirst($device, ['Uptime_Human', 'uptime', 'Uptime'], '-');
            $rxPower   = $this->getVPFirst($device, ['RXPower', 'rx_power', 'RxPower'], '-');
            $pppoeUser = $this->getVPFirst($device, ['pppoeUsername', 'pppoeUsername2', 'PPPoEUsername'], '-');

            // --- FILTER DISCOVERY ---
            // Lewati jika Serial Number tidak ada atau data minimum tidak terpenuhi
            if ($sn === '-' || $sn === '' || $brand === 'Unknown') {
                continue; 
            }

            Device::updateOrCreate(
                ['genieacs_id' => $device['_id']],
                [
                    'brand'          => $brand,
                    'sn'             => $sn,
                    'ssid'           => $ssid,
                    'ip_pppoe'       => $ipPppoe,
                    'ip_tr069'       => $ipTr069,
                    'active_devices' => is_numeric($activeDev) ? $activeDev : '0',
                    'uptime'         => $uptime,
                    'rx_power'       => $rxPower,
                    'pppoe_username' => $pppoeUser,
                ]
            );
            $count++;
        }

        Log::info("[SyncDevicesJob] Sync selesai. {$count} device diperbarui dari GenieACS.");
    }

    /**
     * Catat kegagalan job.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[SyncDevicesJob] Job gagal: ' . $exception->getMessage());
    }

    // ──────────────────────────────────────────────────────────────────────
    // Helper VP — duplikat dari Controller agar Job bisa berdiri sendiri
    // ──────────────────────────────────────────────────────────────────────

    private function getVP($data, string $vpKey, string $default = '-'): string
    {
        $val = data_get($data, "VirtualParameters.{$vpKey}");
        if ($val === null) return $default;

        if (is_string($val) || is_numeric($val)) {
            $str = trim((string) $val);
            return ($str !== '' && $str !== 'N/A') ? $str : $default;
        }

        if (is_array($val) && array_key_exists('_value', $val)) {
            $v = trim((string) $val['_value']);
            return ($v !== '' && $v !== 'N/A') ? $v : $default;
        }

        return $default;
    }

    private function getVPFirst($data, array $keys, string $default = '-'): string
    {
        foreach ($keys as $key) {
            $val = $this->getVP($data, $key);
            if ($val !== '-') return $val;
        }
        return $default;
    }
}
