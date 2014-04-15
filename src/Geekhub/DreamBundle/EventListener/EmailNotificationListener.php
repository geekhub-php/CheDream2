<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.04.14
 * Time: 9:57
 */

namespace Geekhub\DreamBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\Status;
use Geekhub\UserBundle\Entity\User;
use Hip\MandrillBundle\Dispatcher;
use Hip\MandrillBundle\Message;

class EmailNotificationListener
{
    protected $mandrillDispatcher;

    protected $templating;

    private $am;

    function __construct(Dispatcher $mandrillDispatcher, $adminEmail, \Twig_Environment $twig)
    {
        $this->mandrillDispatcher = $mandrillDispatcher;
        $this->am = $adminEmail;
        $this->templating = $twig;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        $em = $args->getEntityManager();


        if ($object instanceof Status) {
            $this->postStatusEmail($em, $object->getTitle(), $object->getDream());
        }

        if ('Geekhub\DreamBundle\Entity\AbstractContribute' == get_parent_class($object)) {
            $this->sendEmail(
                $this->getTemplate(
                    'GeekhubResourceBundle:Email:contribution.html.twig',
                    array(
                        'dream' => $object->getDream(),
                        'contributor' => $object
                    )
                ),
                $object->getUser()->getEmail(),
                'Підтримка мрії'
            );

        }
    }

    protected function sendEmail($body, $to, $subject)
    {
        $message = new Message();
        $message->setFromEmail('test@gmail.com')
            ->setFromName('Черкаська мрія')
            ->addTo($to)
            ->setSubject($subject)
            ->setHtml($body)
        ;

        $this->mandrillDispatcher->send($message);
    }

    protected function postStatusEmail(EntityManager $em, $status, Dream $dream)
    {
        if (in_array($status, array('success', 'implementing'))) {
            $users = $em
                ->getRepository('GeekhubDreamBundle:Dream')
                ->getArrayContributorsByDream($dream)
            ;
        }

        if (in_array($status, array('submitted', 'completed'))) {
            $user = new User();
            $user->setEmail($this->am);
            $users = array($user);
        }

        if (in_array($status, array('rejected', 'collecting-resources', 'fail'))) {
            $users = array($dream->getAuthor());
        }

        if (isset($users)) {
            foreach ($users as $user) {
                $this->sendEmail(
                    $this->getTemplate(
                        'GeekhubResourceBundle:Email:'.$status.'.html.twig',
                        array(
                            'dream' => $dream,
                            'contributor' => $user
                        )
                    ),
                    $user->getEmail(),
                    $status
                );
            }
        }
    }

    protected function getTemplate($nameTwigTemplate, $options = array())
    {
        return $this->templating
            ->loadTemplate($nameTwigTemplate)
            ->render($options)
        ;
    }
}
