<?php


namespace Demands\Application\Command;


use Demands\Domain\Demand;

class DownloadDemand
{
    /**
     * @var Demand
     */
    private $demand;

    /**
     * PrintDemand constructor.
     * @param $demand
     */
    public function __construct(Demand $demand)
    {
        $this->demand = $demand;
    }

    public function getDemand(): Demand
    {
        return $this->demand;
    }
}