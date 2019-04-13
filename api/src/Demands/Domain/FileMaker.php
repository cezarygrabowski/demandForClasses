<?php


namespace Demands\Domain;


use Demands\Domain\DTO\ExportDemandDto;

interface FileMaker
{
    /**
     * @deprecated
     * @param ExportDemandDto[] $exportDemands
     */
    public function makeFile(array $exportDemands);
    public function prepareFileContent(array $exportDemands): string;
}