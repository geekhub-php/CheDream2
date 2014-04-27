<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 06.04.14
 * Time: 1:29
 */

namespace Geekhub\DreamBundle\Admin;

use Geekhub\DreamBundle\Entity\Status;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class DreamAdmin extends Admin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $choiceOptions = Status::getStatusesArray();
        $datagridMapper
            ->add('currentStatus',null, array(), 'choice', array(
                    'choices' => $choiceOptions
                ))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title')
            ->add('author.phone')
            ->add('author.firstName')
            ->add('author.lastName')
            ->add('currentStatus', null, array('editable' => true))
            ->add('_action', 'actions', array(
                    'actions' => array(
                        array('template' => 'GeekhubDreamBundle:Admin:edit_button.html.twig'),
                        'delete' => array()
                    )
                ))
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list', 'edit', 'delete'));
    }
}
