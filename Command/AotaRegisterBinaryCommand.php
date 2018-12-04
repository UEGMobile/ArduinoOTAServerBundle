<?php

namespace UEGMobile\ArduinoOTAServerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary;
use UEGMobile\ArduinoOTAServerBundle\Service\ArduinoOTAServerService;

class AotaRegisterBinaryCommand extends ContainerAwareCommand
{
    protected $arduinoOTAServerService;

    protected function configure()
    {
        $this
            ->setName('aotaserver:register:binary')
            ->setDescription('Register new binary to be available in OTA server')
            ->addArgument('binaryName', InputArgument::REQUIRED, 'Binary file name')
            ->addArgument('binaryVersion', InputArgument::REQUIRED, 'Binary version')
            ->addArgument('userAgent', InputArgument::REQUIRED, 'Client user-agent')
            ->addArgument('sdkVersion', InputArgument::REQUIRED, 'Firmaware SDK version')
            ->addArgument('binaryPath', InputArgument::REQUIRED, 'Binary file path')
        ;
    }

    public function setOTAServerService(ArduinoOTAServerService $arduinoOTAServerService){
        $this->arduinoOTAServerService = $arduinoOTAServerService;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $binaryName = $input->getArgument('binaryName');
        $binaryVersion = $input->getArgument('binaryVersion');
        $userAgent = $input->getArgument('userAgent');
        $sdkVersion = $input->getArgument('sdkVersion');
        $binaryPath = $input->getArgument('binaryPath');
        
        $content = @file_get_contents($binaryPath);
        if($content === FALSE){
            $output->writeln('Cannot access '.$binaryPath.' to read contents.');
        }else{

            try{
                $binary = $this->arduinoOTAServerService->registerBinary(
                    $binaryName,
                    $binaryVersion,
                    $userAgent,
                    $sdkVersion,
                    $content
                );
                $em = $this->getContainer()->get('doctrine')->getManager();
                $em->flush();
        
                $output->writeln('Register '.$binary->getBinaryName().' with id '.$binary->getId().' done!');    
            }catch(\Exception $e){
                $output->writeln('Cannot register: '.$e->getMessage());            
            }
        }        
    }
}