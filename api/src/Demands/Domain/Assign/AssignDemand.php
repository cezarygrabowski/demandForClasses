<?php


namespace Demands\Domain\Assign;


class AssignDemand
{
    /**
     * @var string
     */
    public $demandUuid;

    /**
     * User who assignes a demand
     * @var string
     */
    public $assignorUuid;

    /**
     * @var LectureSet[]
     */
    public $lectureSets;

    public static function create(array $data, string $assignorUuid): self
    {
        $assignDemand = new self();
        $assignDemand->assignorUuid = $assignorUuid;
        $assignDemand->demandUuid = $data['demand']['uuid'];

        foreach ($data['demand']['lectureSets'] as $lectureSet) {
            $lecturer = $lectureSet['lecturer'];
            if (!$lecturer) {
                continue;
            }

            $lectureSetDto = new LectureSet();
            $lectureSetDto->assigneeUuid = $lectureSet['lecturer']['uuid'];
            $lectureSetDto->type = $lectureSet['type'];
            $assignDemand->lectureSets[] = $lectureSetDto;
        }

        return $assignDemand;
    }
}