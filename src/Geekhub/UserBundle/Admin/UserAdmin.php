<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.03.14
 * Time: 11:50
 */

namespace Geekhub\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class UserAdmin extends Admin
{
    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by'    => 'lastLogin'  // name of the ordered field
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('username')
            ->add('email')
            ->add('facebookId')
            ->add('vkontakteId')
            ->add('odnoklassnikiId')
            ->add('locked', null, array('required' => false))
            ->add('enabled', null, array('required' => false))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('email')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('avatar', 'string', ['template' => 'GeekhubResourceBundle:Admin:list_image_without_link.html.twig'])
            ->add('firstName', 'string', ['template' => 'GeekhubUserBundle:Admin:user.html.twig', 'label' => 'Name'])
            ->add('email')
            ->add('phone')
            ->add('facebookId', 'boolean', ['label' => 'Fb'])
            ->add('vkontakteId', 'boolean', ['label' => 'Vk'])
            ->add('odnoklassnikiId', 'boolean', ['label' => 'Ok'])
            ->add('lastLogin')
            ->add('locked', null, array('required' => false, 'editable' => true))
            ->add('enabled', null, array('required' => false, 'editable' => true))
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list', 'edit'));
    }
}
