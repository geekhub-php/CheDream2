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
    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by'    => 'updatedAt'  // name of the ordered field
    );

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
            ->add('mediaPoster', 'string', ['template' => 'GeekhubResourceBundle:Admin:list_image_without_link.html.twig'])
            ->add('title')
            ->add('author.phone')
            ->add('author', 'string', ['template' => 'GeekhubDreamBundle:Admin:dream_author.html.twig'])
            ->add('currentStatus', null)
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
