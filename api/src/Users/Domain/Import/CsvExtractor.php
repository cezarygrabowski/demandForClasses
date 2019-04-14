<?php


namespace Users\Domain\Import;


class CsvExtractor
{
    /**
     * @param $fileName
     * @return ImportUser[]
     */
    public static function extract($fileName): array
    {
        $data = self::read($fileName);
        $importUsers = [];
        foreach ($data as $row) {
            $importUsers[] = ImportUser::create($row);
        }

        return $importUsers;
    }

    private static function read($fileName)
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