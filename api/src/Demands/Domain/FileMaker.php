<?php


namespace Demands\Domain;


use Demands\Domain\Export\ExportDemandDto;

interface FileMaker
{
    /**
     * @deprecated
     * @param ExportDemandDto[] $exportDemands
     */
    public function makeFile(array $exportDemands);
    public function prepareFileContent(array $exportDemands): array;
}