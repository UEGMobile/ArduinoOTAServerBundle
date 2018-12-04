<?php

namespace UEGMobile\ArduinoOTAServerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use UEGMobile\ArduinoOTAServerBundle\Entity\OTADevice;
use Symfony\Component\Console\Helper\Table;
use UEGMobile\ArduinoOTAServerBundle\Service\ArduinoOTAServerService;

class AotaListDeviceCommand extends ContainerAwareCommand
{
    protected $arduinoOTAServerService;

    protected function configure()
    {
        $this
            ->setName('aotaserver:list:devices')
            ->setDescription('List all devices availables in OTA server')
        ;
    }

    public function setOTAServerService(ArduinoOTAServerService $arduinoOTAServerService){
        $this->arduinoOTAServerService = $arduinoOTAServerService;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $devices = $this->arduinoOTAServerService->searchDevices(
            null,
            1,
            10000
        );

        $arrayLines = array();
        $i = 0;
        foreach ($devices->getCurrentPageResults() as $device) {

            $arrayLine = array(
                    $device->getId(),
                    $device->getMac(),
                    $device->isActive()?'Y':'N',
                    $device->getProgram()->getId().' - '. $device->getProgram()->getProgramName(),
                    $device->getMode());
            $arrayLines[$i] = $arrayLine;
            $i++;
        }  
        $table = new Table($output);
        $table
            ->setHeaders(array('Id', 'MAC Address', 'Active', 'Program', 'Mode'))
            ->setRows($arrayLines);
        $table->render();
    }

}
