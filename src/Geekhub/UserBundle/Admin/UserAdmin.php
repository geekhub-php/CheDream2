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
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
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
            ->add('email')
            ->add('facebookId')
            ->add('vkontakteId')
            ->add('odnoklassnikiId')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('email')
            ->add('phone')
            ->add('roles')
            ->add('facebookId')
            ->add('vkontakteId')
            ->add('odnoklassnikiId')
            ->add('locked', null, array('required' => false))
            ->add('enabled', null, array('required' => false))
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list', 'edit'));
    }
}
