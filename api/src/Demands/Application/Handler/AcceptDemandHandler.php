<?php


namespace Demands\Application\Handler;


use Demands\Application\Command\AcceptDemand;
use Demands\Domain\StatusResolver;

class AcceptDemandHandler
{
    /**
     * @var StatusResolver
     */
    private $statusResolver;

    public function __construct(StatusResolver $statusResolver)
    {
        $this->statusResolver = $statusResolver;
    }

    public function handle(AcceptDemand $command)
    {
        $command->getDemand()->setStatus(
            $this->statusResolver->resolveStatusWhenDemandIsAccepted($command->getDemand(), $command->getUser())
        );
    }
}