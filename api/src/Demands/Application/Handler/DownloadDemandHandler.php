<?php


namespace Demands\Application\Handler;


use Demands\Application\Command\DownloadDemand;
use mikehaertl\wkhtmlto\Pdf;
use Twig\Environment;
use Zend\EventManager\Exception\DomainException;

class DownloadDemandHandler
{
    private $twig;

    /**
     * DownloadDemandHandler constructor.
     * @param $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


    public function handle(DownloadDemand $command): Pdf
    {
        $html = $this->twig->render('@demands/demand_pdf.html.twig', [
            'demand' => $command->getDemand()
        ]);

        $pdf = new Pdf($html);

        if (!$pdf->saveAs('/../../../../../var/tmp/test.pdf')) {
            throw new DomainException("Podczas tworzenia pdfa wystąpił błąd: " . $pdf->getError());
        }

        return $pdf;
    }
}