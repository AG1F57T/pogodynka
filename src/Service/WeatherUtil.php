<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Measurement;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;

class WeatherUtil
{
    public function __construct(
        private readonly MeasurementRepository $measurementRepository,
        private readonly LocationRepository $locationRepository,
    ) {
    }

    /**
     * @return Measurement[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        return $this->measurementRepository->findByLocation($location);
    }

    /**
     * @return Measurement[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $city,
        ]);

        return $this->getWeatherForLocation($location);
    }

    public function index(
        #[MapQueryParameter('country')] string $country,
        #[MapQueryParameter('city')] string $city,
        WeatherUtil $util,
    ): JsonResponse
    {
        $measurements = $util->getWeatherForCountryAndCity($country, $city);

        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => array_map(fn(Measurement $m) => [
                'date' => $m->getDate()->format('Y-m-d'),
                'celsius' => $m->getCelsius(),
            ], $measurements),
        ]);
    }
}
