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
        $container = $this->getMock('Symfony\Component\DependencyInjection\Container');
        $mediaManager = new MediaManager('Sonata\MediaBundle\Model\MediaInterface', $this->createRegistryMock());
        $container->expects($this->matches('sonata.media.manager.media'))
            ->method('get')
            ->will($this->returnValue($mediaManager));

        $container->expects($this->matches('serializer'))
            ->method('get')
            ->will($this->returnValue($this->serializer));
        $kernelWebDir = '/var/www/CheDream2/app';
        $uploadDir = '/upload/';
        $vkontakteProvider = new VkontakteProvider($container, $kernelWebDir, $uploadDir);
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
        $this->assertEquals($filledUser->getEmail(), $email);
        $avatarPath = $filledUser->getAvatar()->getBinaryContent();
        $this->assertNotEmpty($avatarPath);
    }

    public function getFileLocationsData()
    {
        return array(
            array('Ivan', '', 'Ivanov', 'ivanov@mail.ru',123456,'dlfkjsldkfjs'),
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

    protected function getFormat()
    {
        return 'json';
    }

    protected function setUp()
    {
        $this->factory = new MetadataFactory(new AnnotationDriver(new AnnotationReader()));

        $this->handlerRegistry = new HandlerRegistry();
        $this->handlerRegistry->registerSubscribingHandler(new ConstraintViolationHandler());
        $this->handlerRegistry->registerSubscribingHandler(new DateHandler());
        $this->handlerRegistry->registerSubscribingHandler(new FormErrorHandler(new IdentityTranslator(new MessageSelector())));
        $this->handlerRegistry->registerSubscribingHandler(new PhpCollectionHandler());
        $this->handlerRegistry->registerSubscribingHandler(new ArrayCollectionHandler());
        $this->handlerRegistry->registerHandler(GraphNavigator::DIRECTION_SERIALIZATION, 'AuthorList', $this->getFormat(),
            function(VisitorInterface $visitor, $object, array $type, Context $context) {
                return $visitor->visitArray(iterator_to_array($object), $type, $context);
            }
        );
        $this->handlerRegistry->registerHandler(GraphNavigator::DIRECTION_DESERIALIZATION, 'AuthorList', $this->getFormat(),
            function(VisitorInterface $visitor, $data, $type, Context $context) {
                $type = array(
                    'name' => 'array',
                    'params' => array(
                        array('name' => 'integer', 'params' => array()),
                        array('name' => 'JMS\Serializer\Tests\Fixtures\Author', 'params' => array()),
                    ),
                );

                $elements = $visitor->getNavigator()->accept($data, $type, $context);
                $list = new AuthorList();
                foreach ($elements as $author) {
                    $list->add($author);
                }

                return $list;
            }
        );

        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new DoctrineProxySubscriber());

        $namingStrategy = new SerializedNameAnnotationStrategy(new CamelCaseNamingStrategy());
        $objectConstructor = new UnserializeObjectConstructor();
        $this->serializationVisitors = new Map(array(
            'json' => new JsonSerializationVisitor($namingStrategy),
            'xml'  => new XmlSerializationVisitor($namingStrategy),
            'yml'  => new YamlSerializationVisitor($namingStrategy),
        ));
        $this->deserializationVisitors = new Map(array(
            'json' => new JsonDeserializationVisitor($namingStrategy),
            'xml'  => new XmlDeserializationVisitor($namingStrategy),
        ));

        $this->serializer = new Serializer($this->factory, $this->handlerRegistry, $objectConstructor, $this->serializationVisitors, $this->deserializationVisitors, $this->dispatcher);
    }
}
