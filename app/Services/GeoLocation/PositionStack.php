<?php

namespace App\Services\GeoLocation;

use App\DTO\Address;
use Illuminate\Support\Facades\Http;

class PositionStack implements GeoLocationInterface
{
    private $geoLocationData;
    private $config;

    public function __construct()
    {
        $this->config = $this->getConfig();
    }

    public function resolveGeoLocationData(Address $address): void
    {
        $response = Http::get($this->config['url'], $this->prepareQuery($address->getDescription()));
        $this->geoLocationData = json_decode($response->body(),true);
    }

    public function getResponse(): array
    {
        return $this->geoLocationData;
    }

    public function getLongitude(): float
    {
        return $this->geoLocationData['data'][0]['longitude'];
    }

    public function getLatitude(): float
    {
        return $this->geoLocationData['data'][0]['latitude'];
    }

    public function isSuccessful(): float
    {
        return array_key_exists('data', $this->geoLocationData);
    }

    private function getConfig(): array
    {
        return config('services.positionstack');
    }

    private function prepareQuery(string $addressDescription): array
    {
        return ['access_key' => $this->config['access_key'], 'query' => $addressDescription];
    }
}