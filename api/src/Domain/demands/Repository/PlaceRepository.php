<?php


namespace App\Domain\demands\Repository;


use App\Domain\demands\Place;

interface PlaceRepository
{
    public function findOneByBuildingAndRoom(int $building, int $room): ?Place;
}
