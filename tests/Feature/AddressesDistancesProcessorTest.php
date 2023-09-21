<?php

namespace Tests\Feature;

use App\Services\GeoLocation\GeoLocationInterface;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class AddressesDistancesProcessorTest extends TestCase
{
    public $expectedFileContent= 'SortNumber,Distance,Name,Address
1,"120.45 km","Eastern Enterprise B.V.","Deldenerstraat 70, 7551AH Hengelo, The Netherlands"
';
    /**
     * A basic test example.
     */
    public function test_console_command(): void
    {
        $this->instance(
            GeoLocationInterface::class,
            Mockery::mock(GeoLocationInterface::class, function (MockInterface $mock) {
                $mock->shouldReceive('resolveGeoLocationData')->twice(2);
                $mock->shouldReceive('getLatitude')->twice()->andReturnValues([51.6882 ,52.26633]);
                $mock->shouldReceive('getLongitude')->twice(2)->andReturnValues([5.298532,6.78576]);
            })
        );
        $this->artisan('app:addresses-distances-processor /eastern-enterprise/data/addresses/input/test/addresses.txt 
        /eastern-enterprise/data/addresses/output/test/addresses.csv')->assertExitCode(0);
        $this->assertStringEqualsFile('data/addresses/output/test/addresses.csv', $this->expectedFileContent);
    }
}
