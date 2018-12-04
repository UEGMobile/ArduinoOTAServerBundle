<?php

namespace UEGMobile\ArduinoOTAServerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary;
use UEGMobile\ArduinoOTAServerBundle\Entity\OTADeviceMac;
use UEGMobile\ArduinoOTAServerBundle\Service\ArduinoOTAServerService;

class AotaUpdateDeviceCommand extends ContainerAwareCommand
{
    protected $arduinoOTAServerService;

    protected function configure()
    {
        $this
            ->setName('aotaserver:update:device')
            ->setDescription('Update a Device')
            ->addArgument('deviceId', InputArgument::REQUIRED, 'Device to update')
            ->addArgument('programId', InputArgument::OPTIONAL, 'Program Id')
            ->addArgument('mode', InputArgument::OPTIONAL, 'Mode (ALPHA, BETA, PROD)', "ALPHA")
            ->addArgument('active', InputArgument::OPTIONAL, '1-ACTIVE or 0-DESACTIVE', 1)
        ;
    }

    public function setOTAServerService(ArduinoOTAServerService $arduinoOTAServerService){
        $this->arduinoOTAServerService = $arduinoOTAServerService;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $deviceId = $input->getArgument('deviceId');
        $programId = $input->getArgument('programId');
        $mode = $input->getArgument('mode');
        $active = $input->getArgument('active');

        try{
            $device = $this->arduinoOTAServerService->getDevice($deviceId);

            if(!is_null($programId)){
                $program = $this->arduinoOTAServerService->getProgram($programId);
                $device->setProgram($program);
            }
            
            if(!is_null($mode)){
                $device->setMode($mode);
            }
            if(!is_null($active)){
                if($active == 0){
                    $device->setActive(false);
                }else{
                    $device->setActive(true);                    
                }
            }
            $em = $this->getContainer()->get('doctrine')->getManager();
            $em->flush();
    
            $output->writeln('Updated '.$device->getMac().' with id '.$device->getId().' done!');    
        }catch(\Exception $e){
            $output->writeln('Cannot update: '.$e->getMessage());            
        }
    }

}