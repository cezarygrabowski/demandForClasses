<?php


namespace Demands\Domain\Update;


class DetailsToUpdate
{
    /**
     * @var string
     */
    public $demandUuid;

    /**
     * @var LectureSet[]
     */
    public $lectureSets;


    public static function fromData($data): self
    {
        $detailsToUpdate = new self();
        foreach ($data['demand']['lectureSets'] as $lectureSet) {
            $lectureSetDto = new LectureSet();
            $lectureSetDto->notes = $lectureSet['notes'];
            $lectureSetDto->type = \Demands\Domain\LectureSet::LECTURE_TYPES_STRING_TO_INT[$lectureSet['type']];
            foreach ($lectureSet['allocatedWeeks'] as $key => $allocatedWeek) {
                if(!$allocatedWeek) {
                    continue;
                }

                $allocatedWeekDto = new AllocatedWeek();
                $allocatedWeekDto->hours = $allocatedWeek['allocatedHours'];
                $allocatedWeekDto->weekNumber = $key;
                $allocatedWeekDto->building = $allocatedWeek['building'];
                $allocatedWeekDto->room = $allocatedWeek['room'];

                $lectureSetDto->allocatedWeeks[] = $allocatedWeekDto;
            }
            $detailsToUpdate->lectureSets[] = $lectureSetDto;
        }

        return $detailsToUpdate;
    }
}
