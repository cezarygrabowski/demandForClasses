<?php


namespace Demands\Domain\Import\StudyPlan;

use Demands\Domain\Import\StudyPlan\Csv\CsvPositions;

class StudyPlansExtractor
{
    /**
     * @param string $fileName
     * @return StudyPlan[]
     */
    public static function extract(string $fileName): array
    {
        $data = self::read($fileName);
        $studyPlans = [];
        foreach ($data as $row) {
            $studyPlans[] = StudyPlan::create($row, new CsvPositions());
        }

        return $studyPlans;
    }

    public static function read(string $fileName): array
    {
        $file = fopen($fileName, 'r');
        $data = [];
        while (($line = fgetcsv($file)) !== FALSE) {
            $data[] = $line;
        }
        fclose($file);

        /** remove first element which is header */
        array_shift($data);

        return $data;
    }
}