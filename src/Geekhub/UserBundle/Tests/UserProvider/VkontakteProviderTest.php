<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.04.14
 * Time: 16:37
 */

namespace Geekhub\UserBundle\Tests\UserProvider;

use Doctrine\Common\Annotations\AnnotationReader;
use Geekhub\ResourceBundle\Tests\SecurityMethods;
use Geekhub\UserBundle\Entity\User;
use Geekhub\UserBundle\UserProvider\AbstractSocialNetworkProvider;
use Geekhub\UserBundle\UserProvider\FacebookProvider;
use Geekhub\UserBundle\UserProvider\VkontakteProvider;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use JMS\Serializer\Construction\UnserializeObjectConstructor;
use JMS\Serializer\Context;
use JMS\Serializer\EventDispatcher\Subscriber\DoctrineProxySubscriber;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\ArrayCollectionHandler;
use JMS\Serializer\Handler\ConstraintViolationHandler;
use JMS\Serializer\Handler\DateHandler;
use JMS\Serializer\Handler\FormErrorHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Handler\PhpCollectionHandler;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\Serializer;
use JMS\Serializer\Tests\Fixtures\AuthorList;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\XmlDeserializationVisitor;
use JMS\Serializer\XmlSerializationVisitor;
use JMS\Serializer\YamlSerializationVisitor;
use PhpCollection\Map;
use Sonata\MediaBundle\Entity\MediaManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\File;
use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;
use Metadata\MetadataFactory;
use JMS\Serializer\Metadata\Driver\AnnotationDriver;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\Translation\MessageSelector;
use JMS\Serializer\EventDispatcher\EventDispatcher;


class VkontakteProviderTest extends WebTestCase
{
    protected $factory;
    protected $dispatcher;

    /** @var Serializer */
    protected $serializer;
    protected $handlerRegistry;
    protected $serializationVisitors;
    protected $deserializationVisitors;

    /**
     * @dataProvider getFileLocationsData
     */
    public function testSetUserData($firstName, $nickName, $lastName, $email, $vkontakteId, $accessToken)
    {
//        $container = $this->getMock('Symfony\Component\DependencyInjection\Container');
//        $mediaManager = new MediaManager('Sonata\MediaBundle\Model\MediaInterface', $this->createRegistryMock());
        $client = static::createClient();
        $container = $client->getContainer();

//        $container->expects($this->matches('sonata.media.manager.media'))
//            ->method('get')
//            ->will($this->returnValue($mediaManager));
//
//        $container->expects($this->matches('serializer'))
//            ->method('get')
//            ->will($this->returnValue($this->serializer));
        $kernelWebDir = '/var/www/CheDream2/app';
        $uploadDir = '/upload/';
        $defaultAvatarPath= '/../web/images/default_avatar.png';
        $vkontakteProvider = new VkontakteProvider($container, $kernelWebDir, $uploadDir, $defaultAvatarPath);
        $user = new User();
        $user->setVkontakteId($vkontakteId);
        $response = new PathUserResponse();
        $responseArray = array(
            'response'=> array(
                0 => array(
                    'first_name' => $firstName,
                    'nickname'  => $nickName,
                    'last_name'  => $lastName,
                    'email'     => $email,
                )
            )
        );
        $response->setResponse($responseArray);
        $token = new OAuthToken($accessToken);
        $response->setOAuthToken($token);
        $filledUser = $vkontakteProvider->setUserData($user, $response);
        $this->assertEquals($filledUser->getFirstName(), $firstName);
        $this->assertEquals($filledUser->getMiddleName(), $nickName);
        $this->assertEquals($filledUser->getLastName(), $lastName);
        //$this->assertEquals($filledUser->getEmail(), $email); //because of the fake email usage
        $avatarPath = $filledUser->getAvatar()->getBinaryContent();
        $this->assertNotEmpty($avatarPath);
    }

    public function getFileLocationsData()
    {
        return array(
            array('Ivan', '', 'Ivanov', 'chedreamtester@gmail.com', 251113893, '7d7543e1d8eab2f47b3bbf064802e451d85f93508e380358d94c41fd6e8c9590924f8ceb33b7cead130a4'),
        );
    }
}
