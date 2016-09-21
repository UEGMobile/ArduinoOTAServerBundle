<?php

namespace UEGMobile\ArduinoOTAServerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary;
use UEGMobile\ArduinoOTAServerBundle\Entity\OTADeviceMac;

class AotaRegisterMacCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('aotaserver:register:mac')
            ->setDescription('Register a MAC id with a OTA Binary')
            ->addArgument('mac', InputArgument::REQUIRED, 'MAC to update')
            ->addArgument('binaryId', InputArgument::REQUIRED, 'Binary Id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mac = $input->getArgument('mac');
        $binaryId = $input->getArgument('binaryId');

        $oatBinary = $this->getContainer()->get('doctrine')->getManager()
                ->getRepository("UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary")
                ->findOneById($binaryId);
        
        if($oatBinary){
            $deviceMac = new OTADeviceMac();
            $deviceMac->setMac($mac);
            $deviceMac->setOtaBinary($oatBinary);
            
            $em = $this->getContainer()->get('doctrine')->getManager();
            $em->persist($deviceMac);
            $em->flush();

            $output->writeln('Register '.$mac.' for '.$binaryId.' done!');

        }else{
            $output->writeln('Cannot access '.$binaryId.' binary.');            
        }
    }

}