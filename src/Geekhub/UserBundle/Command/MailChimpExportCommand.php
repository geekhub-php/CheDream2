<?php

namespace Geekhub\UserBundle\Command;

use Doctrine\ORM\EntityManager;
use Geekhub\UserBundle\Entity\ExportedUser;
use Geekhub\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MailChimpExportCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('geekhub:user:export-to-mail-chimp');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $qb = $em->createQueryBuilder();

        $exportedUsers    = $em->getRepository('GeekhubUserBundle:ExportedUser')->findAll();
        $exportedUsersIds = array_map(function (ExportedUser $exUser) { return $exUser->getId(); }, $exportedUsers);
        $notExportedUsers = $qb->select('nex')
            ->from('GeekhubUserBundle:User', 'nex')
            ->where($qb->expr()->notIn('nex.id', empty($exportedUsersIds) ? [0] : $exportedUsersIds))
            ->getQuery()
            ->getResult()
        ;

        if (empty($notExportedUsers)) {
            $output->writeln('<comment>Can\'t find not subscribed users</comment>');

            return;
        }

        $batch = array_map(function (User $user) {
            return array(
                'email' => array(
                    'email' => $user->getEmail()
                ),
                'email_type' => 'html'
            );
        }, $notExportedUsers);

        $output->writeln(sprintf('<info>Export %s users email to mailchimp</info>', count($notExportedUsers)));
        $this->getContainer()->get('hype_mailchimp')->getList()->batchSubscribe($batch, false);

        array_map(function (User $user) use ($em) {
            $exportedUser = new ExportedUser();
            $exportedUser->setId($user->getId());

            $em->persist($exportedUser);
        }, $notExportedUsers);

        $em->flush();

        $output->writeln('<info>Export users done</info>');
    }
}
