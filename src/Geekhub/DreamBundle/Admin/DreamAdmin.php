<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 06.04.14
 * Time: 1:29
 */

namespace Geekhub\DreamBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class DreamAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('slug')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('currentStatus')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title')
            ->add('currentStatus')
            ->add('_action', 'actions', array(
                    'actions' => array(
                        array('template' => 'GeekhubDreamBundle:Admin:edit_button.html.twig')
                    )
                ))
        ;
    }
}
