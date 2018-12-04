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

class AotaRegisterDeviceCommand extends ContainerAwareCommand
{

    protected $arduinoOTAServerService;

    protected function configure()
    {
        $this
            ->setName('aotaserver:register:device')
            ->setDescription('Register a Device, and associate with a Program')
            ->addArgument('mac', InputArgument::REQUIRED, 'Device MAC Address')
            ->addArgument('programId', InputArgument::REQUIRED, 'Program Id')
            ->addArgument('mode', InputArgument::REQUIRED, 'Mode (ALPHA, BETA, PROD)')
        ;
    }

    public function setOTAServerService(ArduinoOTAServerService $arduinoOTAServerService){
        $this->arduinoOTAServerService = $arduinoOTAServerService;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $deviceMAC = $input->getArgument('mac');
        $programId = $input->getArgument('programId');
        $mode = $input->getArgument('mode');

        try{
            $program = $this->arduinoOTAServerService->getProgram($programId);
            $device = $this->arduinoOTAServerService->registerDevice(
                $deviceMAC,
                $program,
                $mode
            );
            $em = $this->getContainer()->get('doctrine')->getManager();
            $em->flush();
    
            $output->writeln('Register '.$deviceMAC.' with id '.$device->getId().' done!');    
        }catch(\Exception $e){
            $output->writeln('Cannot register: '.$e->getMessage());            
        }
    }

}