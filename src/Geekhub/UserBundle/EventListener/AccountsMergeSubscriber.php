<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25.03.14
 * Time: 22:57
 */

namespace Geekhub\UserBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Geekhub\UserBundle\Entity\MergeRequest;
use Geekhub\UserBundle\Entity\User;
use Hip\MandrillBundle\Message;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Util\SecureRandom;
use Symfony\Component\Form\Exception\NotValidException;

class AccountsMergeSubscriber implements EventSubscriber
{
    protected $container;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'preUpdate',
            'prePersist',
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof User) {

            $email = $object->getEmail();
            $em = $this->container->get('doctrine')->getManager();
            $userWithTheSameEmail = $em->getRepository('GeekhubUserBundle:User')->findOneByEmail($email);

            if ($userWithTheSameEmail && ($userWithTheSameEmail->getId() != $object->getId())) {
                $mergeRequest =  new MergeRequest();
                $mergeRequest->setProposersId($object->getId());
                $mergeRequest->setMergingUserId($userWithTheSameEmail->getId());
                $hash = $this->getUniqueHash();
                $mergeRequest->setHash($hash);
                $em->persist($mergeRequest);
                $object->setEmail($args->getOldValue('email'));
                $object->setEmailCanonical($args->getOldValue('email'));
                $object->setRegistrationStatus(-2);//User::WANTS_MERGE);
                $this->sendMergeNotificationEmail($userWithTheSameEmail, $object, $hash);
                $em->flush();
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof User) {

            $email = $object->getEmail();
            $em = $this->container->get('doctrine')->getManager();
            $userWithTheSameEmail = $em->getRepository('GeekhubUserBundle:User')->findOneByEmail($email);

            if ($userWithTheSameEmail && ($userWithTheSameEmail->getId() != $object->getId())) {
                $mergeRequest =  new MergeRequest();
                $mergeRequest->setProposersId($object->getId());
                $mergeRequest->setMergingUserId($userWithTheSameEmail->getId());
                $hash = $this->getUniqueHash();
                $mergeRequest->setHash($hash);
                $em->persist($mergeRequest);
                $object->setEmail('fake_'.$object->getId().'@example.com');
                $object->setEmailCanonical($object->getEmail());
                $object->setRegistrationStatus(-2);//User::WANTS_MERGE);
                $this->sendMergeNotificationEmail($userWithTheSameEmail, $object, $hash);
                $em->flush();
                exit;
            }
        }
    }

    private function sendMergeNotificationEmail(User $userWithTheSameEmail, User $userWhoAsksMerge, $hash)
    {
        $dispatcher = $this->container->get('hip_mandrill.dispatcher');

        $message = new Message();

        $body = $this->container->get('templating')->render(
            'GeekhubResourceBundle:Email:accounts_merge_notification.html.twig',
                array(
                    'user' => $userWithTheSameEmail->getFirstName()." ".$userWithTheSameEmail->getLastName(),
                    'userWhoAsksMerge' => $userWhoAsksMerge->getFirstName()." ".$userWhoAsksMerge->getLastName(),
                    'hash' => $hash,
                )
            );

        $senderName = $this->container->getParameter('sender_name');
        $senderMail = $this->container->getParameter('sender_mail');
        $message->setFromEmail($senderMail)
            ->setFromName($senderName)
            ->addTo($userWithTheSameEmail->getEmail())
            ->setSubject('Accounts merge request')
            ->setHtml($body);

        $dispatcher->send($message);
    }

    private function getUniqueHash()
    {
        //$generator = new SecureRandom();
        return  uniqid();//md5($generator->nextBytes(10));
    }

    public function mergeUsers(User $proposer, User $mergingUser)
    {
        $em = $this->container->get('doctrine')->getManager();

        if ($proposer->getFacebookId() && !$mergingUser->getFacebookId()) {
            $mergingUser->setFacebookId($proposer->getFacebookId());
        }
        if ($proposer->getVkontakteId() && !$mergingUser->getVkontakteId()) {
            $mergingUser->setVkontakteId($proposer->getVkontakteId());
        }
        if ($proposer->getOdnoklassnikiId() && !$mergingUser->getOdnoklassnikiId()) {
            $mergingUser->setOdnoklassnikiId($proposer->getOdnoklassnikiId());
        }

        foreach ($proposer->getFavoriteDreams() as $dream) {
            if (!$mergingUser->getFavoriteDreams()->contains($dream)) {
                $mergingUser->addFavoriteDream($dream);
            }
        }

        $em->flush();
        $query = $em->createQuery(
           "UPDATE GeekhubDreamBundle:Dream d SET d.author = :newAuthor 
            WHERE d.author = :currentAuthor"
        )->setParameter('currentAuthor', $proposer)
         ->setParameter('newAuthor', $mergingUser);

        $query->getResult();

        
        $query = $em->createQuery(
           "UPDATE GeekhubDreamBundle:FinancialContribute c SET c.user = :newAuthor 
            WHERE c.user = :currentAuthor"
        )->setParameter('currentAuthor', $proposer)
         ->setParameter('newAuthor', $mergingUser);
        
        $query->execute();

        $query = $em->createQuery(
           "UPDATE GeekhubDreamBundle:EquipmentContribute c SET c.user = :newAuthor 
            WHERE c.user = :currentAuthor"
        )->setParameter('currentAuthor', $proposer)
         ->setParameter('newAuthor', $mergingUser);
        
        $query->execute();

        $query = $em->createQuery(
           "UPDATE GeekhubDreamBundle:WorkContribute c SET c.user = :newAuthor 
            WHERE c.user = :currentAuthor"
        )->setParameter('currentAuthor', $proposer)
         ->setParameter('newAuthor', $mergingUser);
        
        $query->execute();

        $query = $em->createQuery(
           "UPDATE GeekhubDreamBundle:OtherContribute c SET c.user = :newAuthor 
            WHERE c.user = :currentAuthor"
        )->setParameter('currentAuthor', $proposer)
         ->setParameter('newAuthor', $mergingUser);
        
        $query->execute();

        $em->remove($proposer);
        $em->flush();
    }
}
