<?php


namespace Demands\Application\Command;


use Demands\Domain\Demand;
use Demands\Domain\Update\DetailsToUpdate;

class UpdateDemand
{
    /**
     * @var DetailsToUpdate
     */
    private $detailsToUpdate;

    /**
     * @var Demand
     */
    private $demand;

    /**
     * UpdateDemand constructor.
     * @param DetailsToUpdate $detailsToUpdate
     * @param Demand $demand
     */
    public function __construct(
        DetailsToUpdate $detailsToUpdate,
        Demand $demand
    ) {
        $this->demand = $demand;
        $this->detailsToUpdate = $detailsToUpdate;
    }

    public function getDetailsToUpdate(): DetailsToUpdate
    {
        return $this->detailsToUpdate;
    }

    public function getDemand(): Demand
    {
        return $this->demand;
    }
}
