<?php

namespace Geekhub\DreamBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UnRemoveDreamsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('geekhub:dream:unremove-dreams')
            ->setDescription('Revert delete dreams')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->getFilters()->disable('softdeleteable');

        $dreams = $em->getRepository('GeekhubDreamBundle:Dream')->findAll();

        foreach ($dreams as $dream) {
            if ($dream->getDeletedAt()) {
                $dream->setDeletedAt(null);
            }
        }

        $em->flush();
    }
}
