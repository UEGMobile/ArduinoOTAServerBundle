<?php

namespace UEGMobile\ArduinoOTAServerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary;

class AotaRegisterBinaryCommand extends ContainerAwareCommand
{
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
            $otaBinary = new OTABinary();
            $otaBinary->setBinaryName($binaryName);
            $otaBinary->setBinaryVersion($binaryVersion);
            $otaBinary->setUserAgent($userAgent);
            $otaBinary->setSdkVersion($sdkVersion);
            $otaBinary->setBinaryFile($content);        
            
            $em = $this->getContainer()->get('doctrine')->getManager();
            $em->persist($otaBinary);
            $em->flush();
            
            $output->writeln('Register '.$binaryName.' done!');
        }        
    }
}