<?php

namespace App\DTO;

class Address
{
    private string $name;
    private string $description;
    private float $latiude;
    private float $longitude;
    private bool $isMainAddress;
    private float $distanceFromMainAddress;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getLatitude(): float
    {
        return $this->latiude;
    }

    public function setLatitude(string $latitude): void
    {
        $this->latiude = $latitude;
    }

    public function getIsMainAddress(): bool
    {
        return $this->isMainAddress;
    }

    public function setIsMainAddress(bool $isMainAddress): void
    {
        $this->isMainAddress = $isMainAddress;
    }

    public function getDistanceFromMainAddress(): float
    {
        return $this->distanceFromMainAddress;
    }

    public function setDistanceFromMainAddress(float $distanceFromMainAddress): void
    {
        $this->distanceFromMainAddress = $distanceFromMainAddress;
    }
}
