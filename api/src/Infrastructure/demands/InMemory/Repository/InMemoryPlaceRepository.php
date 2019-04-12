<?php


namespace App\Infrastructure\demands\InMemory\Repository;


use App\Domain\demands\Place;
use App\Domain\demands\Repository\PlaceRepository;

class InMemoryPlaceRepository implements PlaceRepository
{
    private $places;

    /**
     * InMemoryPlaceRepository constructor.
     * @param Place[] $places
     */
    public function __construct(array $places)
    {
        foreach ($places as $place) {
            $this->places[$place->getBuilding()][$place->getRoom()] = $place;
        }
    }

    public function findOneByBuildingAndRoom(int $building, int $room): ?Place
    {
        return $this->places[$building][$room];
    }
}
