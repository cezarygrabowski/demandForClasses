<?php


namespace Demands\Domain\Update;


class DetailsToUpdate
{
    /**
     * @var string
     */
    public $notes;

    /**
     * @var LectureSet[]
     */
    public $lectureSets;

    public static function fromData($data): self
    {
        $detailsToUpdate = new self();

        //TODO implement me

        return $detailsToUpdate;
    }
}