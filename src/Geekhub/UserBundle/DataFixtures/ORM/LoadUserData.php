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

        foreach ($this->getUserArray($usersYaml) as $key => $item) {
            $reference = 'avatar-'.$key;
            $this->setMediaContent(
                __DIR__.'/images/'.$key.'.jpg',
                'sonata.media.provider.image',
                $reference
            );

            $user = new User();
            $user->setUsername($key);
            $user->setEmail($key.'@example.com');
            $user->setEnabled(true);
            $user->setPlainPassword(
                $item['password'] == '<current()>' ? $this->replaceValue($item['password'], $key) : $item['password']
            );
            $user->setFirstName(
                $item['firstName'] == '<current()>' ? $this->replaceValue($item['firstName'], $key) : $item['firstName']
            );
            $user->setLastName(
                !isset($item['lastName']) ? '' : $item['lastName']
            );
            $user->setAvatar($this->getReference($reference));
            $user->setRoles($item['roles']);
            $manager->persist($user);

            $this->addReference('user-'.$key, $user);
        }

        $manager->flush();
    }

    /**
     * @param $current
     * @param $value
     *
     * @return mixed
     */
    protected function replaceValue($current, $value)
    {
        return preg_replace("/<current()>/", $value, $current);
    }

    /**
     * @param $ymlData
     *
     * @return mixed
     */
    protected function getUserArray($ymlData)
    {
        foreach ($ymlData as $key => $value) {
            if (preg_match("/,/",$key)) {
                $keys = array_unique(explode(',', $key));
                foreach ($keys as $subKey) {
                    $this->data[trim($subKey)] = $value;
                }
            } else {
                $this->data[$key] = $value;
            }
        }

        return $this->data;
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
