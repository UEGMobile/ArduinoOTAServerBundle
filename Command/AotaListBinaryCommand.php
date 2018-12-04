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

class AotaListBinaryCommand extends ContainerAwareCommand
{
    protected $arduinoOTAServerService;

    protected function configure()
    {
        $this
            ->setName('aotaserver:list:binary')
            ->setDescription('List all binary files availables in OTA server')
        ;
    }

    public function setOTAServerService(ArduinoOTAServerService $arduinoOTAServerService){
        $this->arduinoOTAServerService = $arduinoOTAServerService;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $binaries = $this->arduinoOTAServerService->searchBinaries();

        $arrayLines = array();
        $i = 0;
        foreach ($binaries as $binary) {
            $arrayLine = array(
                    $binary->getId(),
                    $binary->getBinaryName(),
                    $binary->getUserAgent(),
                    $binary->getSdkVersion());
            $arrayLines[$i] = $arrayLine;
            $i++;
        }  
        $table = new Table($output);
        $table
            ->setHeaders(array('Id', 'Binary Name', 'User-Agent', 'SDK Version'))
            ->setRows($arrayLines);
        $table->render();
    }

}
