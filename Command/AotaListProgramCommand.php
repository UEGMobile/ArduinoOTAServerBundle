<?php

namespace UEGMobile\ArduinoOTAServerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary;
use Symfony\Component\Console\Helper\Table;
use UEGMobile\ArduinoOTAServerBundle\Service\ArduinoOTAServerService;

class AotaListProgramCommand extends ContainerAwareCommand
{
    protected $arduinoOTAServerService;

    protected function configure()
    {
        $this
            ->setName('aotaserver:list:programs')
            ->setDescription('List all programs availables in OTA server')
        ;
    }

    public function setOTAServerService(ArduinoOTAServerService $arduinoOTAServerService){
        $this->arduinoOTAServerService = $arduinoOTAServerService;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $programs = $this->arduinoOTAServerService->searchPrograms(
            null,
            1,
            10000
        );

        $arrayLines = array();
        $i = 0;
        foreach ($programs->getCurrentPageResults() as $program) {
            $alphaName = '(none)';
            $bAlpha = $program->getBinaryAlpha();
            if(!is_null($bAlpha)){
                $alphaName = $bAlpha->getId() . ' - '. $bAlpha->getBinaryName();
            }
            $betaName = '(none)';
            $bBeta = $program->getBinaryBeta();
            if(!is_null($bBeta)){
                $betaName = $bBeta->getId() . ' - '. $bBeta->getBinaryName();
            }
            $prodName = '(none)';
            $bProd = $program->getBinaryProd();
            if(!is_null($bProd)){
                $prodName = $bProd->getId() . ' - '. $bProd->getBinaryName();
            }
            $arrayLine = array(
                    $program->getId(),
                    $program->getProgramName(),
                    $alphaName,
                    $betaName,
                    $prodName);
            $arrayLines[$i] = $arrayLine;
            $i++;
        }  
        $table = new Table($output);
        $table
            ->setHeaders(array('Id', 'Binary Name', 'Alpha', 'Beta', 'Prod'))
            ->setRows($arrayLines);
        $table->render();
    }

}
