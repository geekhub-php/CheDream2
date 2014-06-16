<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.04.14
 * Time: 16:37
 */

namespace Geekhub\UserBundle\Tests\UserProvider;

use Geekhub\ResourceBundle\Tests\SecurityMethods;
use Geekhub\UserBundle\Entity\User;
use Geekhub\UserBundle\UserProvider\AbstractSocialNetworkProvider;
use Geekhub\UserBundle\UserProvider\FacebookProvider;
use Sonata\MediaBundle\Entity\MediaManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\File;

class AbstractSocialNetworkProviderTest extends WebTestCase
{
    /**
     * @dataProvider getFileLocationsData
     */
    public function testGetMediaFromRemoteImg($remoteImg, $localFileName, $result)
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\Container');
        $mediaManager = new MediaManager('Sonata\MediaBundle\Model\MediaInterface', $this->createRegistryMock());
        $container->expects($this->any())
            ->method('get')
            ->will($this->returnValue($mediaManager));
        $kernelWebDir = '/var/www/CheDream2/app';
        $uploadDir = '/upload/';
        $facebookProvider = new FacebookProvider($container, $kernelWebDir, $uploadDir);
        $newMedia = $facebookProvider->getMediaFromRemoteImg($remoteImg,$localFileName);
        $fullFileName= $kernelWebDir.'/../web'.$uploadDir.$localFileName;
        $mediaFileName = $newMedia->getBinaryContent();
        $this->assertEquals($mediaFileName, $fullFileName);
    }

    public function getFileLocationsData()
    {
        return array(
            array('http://cs4303.vk.me/u11040263/a_df67310f.jpg', 'avatar1.jpg', true),
            array('http://chedream.local/upload/dream/image/cache/userAvatarBig/upload/media/avatar/0001/01/a3058274bc3e8fab6bbeae05be45b1f37c3f5dde.jpeg', 'avatar2.jpg', true),
            array('http://localhost2/upload/dream/image/cache/userAvatarBig/upload/media/avatar/0001/01/non_existent_avatar.jpeg', 'avatar3.jpg', false),
        );
    }

    /**
     * Returns mock of doctrine document manager.
     *
     * @return \Sonata\DoctrinePHPCRAdminBundle\Model\ModelManager
     */
    protected function createRegistryMock()
    {
        $dm = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();

        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $registry->expects($this->any())->method('getManagerForClass')->will($this->returnValue($dm));

        return $registry;
    }



}
