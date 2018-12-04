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

class AotaUpdateProgramCommand extends ContainerAwareCommand
{
    protected $arduinoOTAServerService;

    protected function configure()
    {
        $this
            ->setName('aotaserver:update:program')
            ->setDescription('Update a Program')
            ->addArgument('programId', InputArgument::REQUIRED, 'Program Id')
            ->addArgument('name', InputArgument::OPTIONAL, 'Program name')
            ->addOption('alpha', null,  InputOption::VALUE_OPTIONAL, 'Alpha Binary Id', false)
            ->addOption('beta', null, InputOption::VALUE_OPTIONAL, 'Beta Binary Id', false)
            ->addOption('prod', null, InputOption::VALUE_OPTIONAL, 'Prod Binary Id', false)
        ;
    }

    public function setOTAServerService(ArduinoOTAServerService $arduinoOTAServerService){
        $this->arduinoOTAServerService = $arduinoOTAServerService;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $programId = $input->getArgument('programId');
        $name = $input->getArgument('name');
        $alpha = $input->getOption('alpha');
        $beta = $input->getOption('beta');
        $prod = $input->getOption('prod');

        try{
            $program = $this->arduinoOTAServerService->getProgram($programId);

            if(!is_null($name)){
                $program->setProgramName($name);
            }
            
            if($alpha !== false){
                $binaryAlpha = $this->arduinoOTAServerService->getBinary($alpha);
                $program->setBinaryAlpha($binaryAlpha);
            }

            if($beta !== false){
                $binaryBeta = $this->arduinoOTAServerService->getBinary($beta);
                $program->setBinaryBeta($binaryBeta);
            }

            if($prod !== false){
                $binaryProd = $this->arduinoOTAServerService->getBinary($prod);
                $program->setBinaryProd($binaryProd);
            }

            $em = $this->getContainer()->get('doctrine')->getManager();
            $em->flush();
    
            $output->writeln('Updated '.$program->getProgramName().' with id '.$program->getId().' done!');    
        }catch(\Exception $e){
            $output->writeln('Cannot update: '.$e->getMessage());            
        }
    }

}