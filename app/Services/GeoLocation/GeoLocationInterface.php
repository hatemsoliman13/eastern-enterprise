<?php

namespace App\Services\GeoLocation;

use App\DTO\Address;

interface GeoLocationInterface
{
    public function resolveGeoLocationData(Address $address): void;
    public function isSuccessful(): float;
    public function getResponse(): array;
    public function getLongitude(): float;
    public function getLatitude(): float;
}