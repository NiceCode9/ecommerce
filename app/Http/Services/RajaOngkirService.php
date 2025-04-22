<?php

namespace App\Http\Services;

use Exception;

class RajaOngkirService
{

    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.rajaongkir.base-url', 'https://api-sandbox.collaborator.komerce.id');
        $this->apiKey = config('services.rajaongkir.api-key');
    }

    public function getWilayah(?string $keyword = null)
    {
        try {
            $curl = curl_init();
            $url = "{$this->baseUrl}/tariff/api/v1/destination/search";

            if ($keyword) {
                $url .= "?keyword=" . urlencode($keyword);
            }

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'x-api-key: ' . $this->apiKey,
                    'Accept: application/json'
                ],
            ]);

            $response = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);

            if ($error) {
                throw new Exception("Curl Error: " . $error);
            }

            return json_decode($response, true);
        } catch (Exception $e) {
            throw new Exception("Failed to get wilayah: " . $e->getMessage());
        }
    }

    public function calculateCost(
        int $shipperDestinationId,
        int $receiverDestinationId,
        int $weight,
        int $itemValue,
        string $cod = 'no'
    ) {
        try {
            $curl = curl_init();
            $url = "{$this->baseUrl}/tariff/api/v1/calculate";
            $params = http_build_query([
                'shipper_destination_id' => $shipperDestinationId,
                'receiver_destination_id' => $receiverDestinationId,
                'weight' => $weight,
                'item_value' => $itemValue,
                'cod' => $cod
            ]);

            curl_setopt_array($curl, [
                CURLOPT_URL => $url . '?' . $params,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'x-api-key: ' . $this->apiKey,
                    'Accept: application/json'
                ],
            ]);

            $response = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);

            if ($error) {
                throw new Exception("Curl Error: " . $error);
            }

            return json_decode($response, true);
        } catch (Exception $e) {
            throw new Exception("Failed to calculate cost: " . $e->getMessage());
        }
    }
}
