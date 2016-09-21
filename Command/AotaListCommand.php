<?php

namespace UEGMobile\ArduinoOTAServerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary;
use Symfony\Component\Console\Helper\Table;

class AotaListCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('aotaserver:list')
            ->setDescription('List all binary files availables in OTA server')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $list = $this->getContainer()->get('doctrine')->getManager()
                ->getRepository("UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary")->findAll();
        
        $arrayLines = array();
        $i = 0;
        foreach ($list as $binary) {
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
