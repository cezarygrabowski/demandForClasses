<?php


namespace Demands\Infrastructure\InMemory\Repository;


use Demands\Domain\Place;
use Demands\Domain\Repository\PlaceRepository;

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

    public function addPlace(Place $place){
        $this->places[$place->getBuilding()][$place->getRoom()] = $place;
    }
}
