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

class AotaRegisterProgramCommand extends ContainerAwareCommand
{

    protected $arduinoOTAServerService;

    protected function configure()
    {
        $this
            ->setName('aotaserver:register:program')
            ->setDescription('Register a Program')
            ->addArgument('name', InputArgument::REQUIRED, 'Program name')
        ;
    }

    public function setOTAServerService(ArduinoOTAServerService $arduinoOTAServerService){
        $this->arduinoOTAServerService = $arduinoOTAServerService;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $name = $input->getArgument('name');

        try{
            $program = $this->arduinoOTAServerService->registerProgram($name);

            $em = $this->getContainer()->get('doctrine')->getManager();
            $em->flush();
    
            $output->writeln('Register Program "'.$program->getProgramName().'" with id '.$program->getId().' done!');    
        }catch(\Exception $e){
            $output->writeln('Cannot register: "'.$e->getMessage());            
        }
    }

}