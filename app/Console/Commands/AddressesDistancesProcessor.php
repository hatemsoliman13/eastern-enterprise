<?php

namespace App\Console\Commands;

use App\DTO\Address;
use App\Services\GeoLocation\GeoLocationInterface;
use Illuminate\Console\Command;
use App\Services\GeoLocation\PositionStack;

class AddressesDistancesProcessor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:addresses-distances-processor {input_file_path=/eastern-enterprise/data/addresses/input/addresses.txt}
        {output_file_path=/eastern-enterprise/data/addresses/output/addresses.csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(private GeoLocationInterface $geoLocationService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $addresses = [];
        try {
            $addressesFile = file($this->argument('input_file_path'));
            foreach ($addressesFile as $addressLine) {

                $addressName = $this->getAddressName($addressLine);
                $addressDescription = $this->getAddressDescription($addressLine);

                $address = $this->createAddress($addressName, $addressDescription);

                $address = $this->addGeoLocation($address);

                $addresses[] = $address;
            }

            $mainAddress = array_shift($addresses);
            $mainAddress->setIsMainAddress(true);
            $mainAddress->setDistanceFromMainAddress(0);

            foreach ($addresses as $address) {
                $distance = $this->calculateDistance($mainAddress, $address);
                $address->setIsMainAddress(false);
                $address->setDistanceFromMainAddress($distance);
            }

            usort($addresses, [$this, 'sortByDistanceFromMainAddress']);


            $formatedAddresses = $this->formatAddresses($addresses);
            $this->printFormatedAddressesToConsole($formatedAddresses);
            $this->saveFormatedAddressesToCsv($formatedAddresses);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    private function sortByDistanceFromMainAddress(Address $a, Address $b): int
    {
        if ($a->getDistanceFromMainAddress() == $b->getDistanceFromMainAddress()) {
            return 0;
        }
        return ($a->getDistanceFromMainAddress() < $b->getDistanceFromMainAddress()) ? -1 : 1;
    }

    private function addGeoLocation(Address $address): Address
    {
        try {
            $this->geoLocationService->resolveGeoLocationData($address);
            $address->setLatitude($this->geoLocationService->getLatitude());
            $address->setLongitude($this->geoLocationService->getLongitude());
        } catch (\Throwable $th) {
            throw new \Exception(sprintf(
                'An Error occured while attempting to resolve GeoLocation for address %s and got response %s',
                $address->getName(),
                json_encode(
                    $this->geoLocationService->getResponse()
                )
            ));
        }

        return $address;
    }

    private function formatAddresses(array $addresses): array
    {
        $formatedAddresses = [];

        foreach ($addresses as $key => $address) {
            $formatedAddresses[] = [
                'SortNumber' => $key + 1,
                'Distance' => (string)$address->getDistanceFromMainAddress() . ' km',
                'Name' => $address->getName(),
                'Address' => $address->getDescription()
            ];
        }

        return $formatedAddresses;
    }

    private function printFormatedAddressesToConsole(array $formatedAddresses): void
    {
        foreach ($formatedAddresses as $formatedAddress) {
            $this->info(json_encode($formatedAddress));
        }
    }

    private function saveFormatedAddressesToCsv(array $formatedAddresses): void
    {
        $csvHeader = ['SortNumber', 'Distance', 'Name', 'Address'];
        array_unshift($formatedAddresses, $csvHeader);
        $fp = fopen($this->argument('output_file_path'), 'w');
        foreach ($formatedAddresses as $formatedAddress) {
            fputcsv($fp, $formatedAddress);
        }
    }

    private function createAddress(string $addressName, string $addressDescription): Address
    {
        $address = new Address();
        $address->setName($addressName);
        $address->setDescription($addressDescription);

        return $address;
    }

    private function getAddressname(string $address): string
    {
        return strstr($address, ' - ', true);
    }

    private function getAddressDescription(string $address): string
    {
        return rtrim(substr(strstr($address, ' - '), 3));
    }

    private function calculateDistance(Address $mainAddress, Address $secodaryAddress): string
    {
        $mainAddressLongitude = deg2rad($mainAddress->getLongitude());
        $secondaryAddressLongitude = deg2rad($secodaryAddress->getLongitude());
        $mainAddressLatitude = deg2rad($mainAddress->getLatitude());
        $secondaryAddressLatitude = deg2rad($secodaryAddress->getLatitude());

        $differenceInLongitude = $secondaryAddressLongitude - $mainAddressLongitude;
        $differenceInLatitude = $secondaryAddressLatitude - $mainAddressLatitude;

        $squareOfHalfTheChordLength = pow(sin($differenceInLatitude / 2), 2) +
            cos($mainAddressLatitude) * cos($secondaryAddressLatitude) * pow(sin($differenceInLongitude / 2), 2);

        $angularDistance = 2 * asin(sqrt($squareOfHalfTheChordLength));

        $earthRadius = 3958.756;
        $milesToKm = 1.60934;

        return number_format($angularDistance * $earthRadius * $milesToKm, 2, '.', '');
    }
}
