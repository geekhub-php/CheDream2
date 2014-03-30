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

class UserAdmin extends Admin
{
    // Поля, отображаемые в формах create/edit
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('username')
            ->add('email')
            ->add('locked', null, array('required' => false))
            ->add('enabled', null, array('required' => false))
        ;
    }

    // Поля, отображаемые в формах фильтров
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('email')
//            ->add('enable')
        ;
    }

    // Поля, отображаемые в списках
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('roles')
            ->add('locked', null, array('required' => false))
            ->add('enabled', null, array('required' => false))
        ;
    }
} 