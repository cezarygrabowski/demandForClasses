<?php

namespace App\DTO;

class Demand
{
    public $id;
    public $subject;
    public $hours;
    public $group;
    public $groupType;
    public $status;

    public static function fromDemand(\App\Entity\Demand $demand): self
    {
        $dto = new Demand();
        $dto->id = $demand->getId();
        $dto->subject = $demand->getSubject()->getName();
        $dto->hours = $demand->getTotalHours();
        $dto->group = $demand->getGroup();
        $dto->groupType = $demand->getGroupType();
        $dto->status = \App\Entity\Demand::STATUSES[$demand->getStatus()];

        return $dto;
    }
}