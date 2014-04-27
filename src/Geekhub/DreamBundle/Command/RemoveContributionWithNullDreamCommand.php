<?php

namespace Geekhub\DreamBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveContributionWithNullDreamCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dream:remove-contributes-with-null-dream')
            ->setDescription('Remove contributes where dream == null')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $equipmentContributions = $this->getContainer()->get('doctrine')->getRepository('GeekhubDreamBundle:EquipmentContribute')->findAll();
        $financialContributions = $this->getContainer()->get('doctrine')->getRepository('GeekhubDreamBundle:FinancialContribute')->findAll();
        $workContributions      = $this->getContainer()->get('doctrine')->getRepository('GeekhubDreamBundle:WorkContribute')->findAll();
        $otherContributions      = $this->getContainer()->get('doctrine')->getRepository('GeekhubDreamBundle:OtherContribute')->findAll();

        $progress = $this->getHelperSet()->get('progress');
        $progress->start($output, count($equipmentContributions) + count($financialContributions) + count($workContributions) + count($otherContributions));

        array_map($this->removeContributionWithNullDream($progress), $equipmentContributions);
        array_map($this->removeContributionWithNullDream($progress), $financialContributions);
        array_map($this->removeContributionWithNullDream($progress), $workContributions);
        array_map($this->removeContributionWithNullDream($progress), $otherContributions);

//        foreach ($dreams as $dream) {
//            $dream->getDreamFinancialContributions()->forAll($this->removeContributionWithNullDream());
//            $dream->getDreamWorkContributions()->forAll($this->removeContributionWithNullDream());
//            $dream->getDreamEquipmentContributions()->forAll($this->removeContributionWithNullDream());
//
//            $progress->advance();
//        }

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $em->flush();

        $progress->finish();
        $output->writeln('Done');
    }

    public function removeContributionWithNullDream($progress)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        return function ($element) use ($em, $progress) {
            $progress->advance();
            
            if (!$element->getDream()) {
                $em->remove($element);
            }
        };
    }
}
