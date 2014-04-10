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
        $choiceOptions = array(
            Status::SUBMITTED => Status::SUBMITTED,
            Status::COLLECTING_RESOURCES => Status::COLLECTING_RESOURCES,
            Status::REJECTED => Status::REJECTED,
            Status::IMPLEMENTING => Status::IMPLEMENTING,
            Status::COMPLETED => Status::COMPLETED,
            Status::SUCCESS => Status::SUCCESS,
            Status::FAIL => Status::FAIL,
        );
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
            ->add('currentStatus')
            ->add('_action', 'actions', array(
                    'actions' => array(
                        array('template' => 'GeekhubDreamBundle:Admin:edit_button.html.twig')
                    )
                ))
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list', 'edit'));
    }
}
