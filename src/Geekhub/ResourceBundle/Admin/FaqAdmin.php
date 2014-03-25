<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.03.14
 * Time: 22:19
 */

namespace Geekhub\ResourceBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;

class FaqAdmin extends Admin
{
    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', 'text', array('label' => 'Заголовок'))
            ->add('question', 'text', array('label' => 'Питання'))
            ->add('answer', 'textarea', array('label' => 'Відповідь', 'attr' => array('rows' => 10)))
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title', 'text', array('label' => 'Заголовок'))
            ->add('question', 'text', array('label' => 'Питання'))
            ->add('answer', 'text', array('label' => 'Відповідь'))
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', 'text', array('label' => 'Заголовок'))
            ->add('question', 'text', array('label' => 'Питання'))
            ->add('answer', 'text', array('label' => 'Відповідь'))
            ->add('_action', 'actions', array(
                    'label' => 'Дії',
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
                    )
                ))
        ;
    }
    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('question')
            ->add('answer')
        ;
    }
    // setup the default sort column and order
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'name'
    );
}
