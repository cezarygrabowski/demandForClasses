<?php


namespace Demands\Application\FileManagers;


use Demands\Domain\Export\ExportDemandDto;
use Demands\Domain\FileMaker;
use Demands\Domain\Group;
use Demands\Domain\LectureSet;

class CsvFileMaker implements FileMaker
{
    /**
     * @param ExportDemandDto[] $exportDemands
     */
    public function makeFile(array $exportDemands)
    {
        //TODO not needed for now
    }

    private function prepareContent(array $exportDemands): array
    {
        $rows = [];
        foreach ($exportDemands as $demand) {
            $rows[] = $this->implode($demand);
        }

        return $rows;
    }

    private function prepareHeaders()
    {
        $headers =  [
            'Typ zajęć',
            'Nr zapotrzebowania',
            'Prowadzący',
            'Przedmiot',
            'Rok szkolny',
            'Grupa',
            'Typ grupy',
            'Semestr',
            'Instytut',
            'Wydział',
            'Zakres godzinowy',
            '1 tydzień - godziny',
            '1 tydzień - budynek',
            '1 tydzień - sala',
            '2 tydzień - godziny',
            '2 tydzień - budynek',
            '2 tydzień - sala',
            '3 tydzień - godziny',
            '3 tydzień - budynek',
            '3 tydzień - sala',
            '4 tydzień - godziny',
            '4 tydzień - budynek',
            '4 tydzień - sala',
            '5 tydzień - godziny',
            '5 tydzień - budynek',
            '5 tydzień - sala',
            '6 tydzień - godziny',
            '6 tydzień - budynek',
            '6 tydzień - sala',
            '7 tydzień - godziny',
            '7 tydzień - budynek',
            '7 tydzień - sala',
            '8 tydzień - godziny',
            '8 tydzień - budynek',
            '8 tydzień - sala',
            '9 tydzień - godziny',
            '9 tydzień - budynek',
            '9 tydzień - sala',
            '10 tydzień - godziny',
            '10 tydzień - budynek',
            '10 tydzień - sala',
            '11 tydzień - godziny',
            '11 tydzień - budynek',
            '11 tydzień - sala',
            '12 tydzień - godziny',
            '12 tydzień - budynek',
            '12 tydzień - sala',
            '13 tydzień - godziny',
            '13 tydzień - budynek',
            '13 tydzień - sala',
            '14 tydzień - godziny',
            '14 tydzień - budynek',
            '14 tydzień - sala',
            '15 tydzień - godziny',
            '15 tydzień - budynek',
            '15 tydzień - sala',
        ];

        return implode(',', $headers);
    }

    private function implode(ExportDemandDto $demand)
    {
        $row = [ //TODO make order easily configurable
            LectureSet::LECTURE_TYPES_INT_TO_STRING[$demand->lectureType],
            $demand->demandUuid,
            $demand->lecturer,
            $demand->subjectName,
            $demand->schoolYear,
            $demand->group,
            Group::GROUP_TYPES_INT_TO_STRING[$demand->groupType],
            $demand->semester,
            $demand->institute,
            $demand->department,
            $demand->hours
        ];

        $row = array_merge($row, $demand->getWeeks());

        return $row;
    }

    public function prepareFileContent(array $exportDemands): array
    {
        $content = $this->prepareContent($exportDemands);
        $headers = $this->prepareHeaders();

        return [
            $headers,
            $content
        ];
    }
}
