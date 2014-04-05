<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.03.14
 * Time: 22:54
 */

namespace Geekhub\UserBundle\DataFixtures\ORM;

use Application\Sonata\MediaBundle\DataFixtures\ORM\AbstractMediaLoader;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Geekhub\UserBundle\Entity\User;
use Symfony\Component\Yaml\Yaml;

class LoadUserData extends AbstractMediaLoader implements OrderedFixtureInterface
{
    protected $data;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $usersYaml = Yaml::parse($this->getYmlFile());

        foreach ($usersYaml as $key => $item) {
            $reference = 'avatar-'.$key;
            $this->setMediaContent(
                __DIR__.'/images/'.$key.'.jpg',
                'avatar',
                'sonata.media.provider.image',
                $reference
            );

            $user = new User();

            $user->setUsername($key);
            $user->setEmail(
                $key != 'admin' ? $key.'@example.com' : $this->container->getParameter('admin.mail')
            );
            $user->setEnabled(true);
            $user->setPlainPassword($key);
            $user->setFirstName(array_key_exists('firstName', $item) ? $item['firstName'] : null);
            $user->setLastName(array_key_exists('lastName', $item) ? $item['lastName'] : null);
            $user->setAvatar($this->getReference($reference));
            $user->setRoles(!isset($item['roles']) ? array('ROLE_USER') : $item['roles']);

            $manager->persist($user);

            $this->addReference('user-'.$key, $user);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }

    /**
     * @return string
     */
    protected function getYmlFile()
    {
        return __DIR__ . '/Data/User.yml';
    }
}
