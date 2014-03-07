<?php

namespace Geekhub\ResourceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;

class PageController extends Controller
{
    /**
     * @View(templateVar = "form")
     */
    public function contactsAction()
    {
        $form = $this->createFormBuilder()
            ->add('to', 'email', array('label' => 'АДРЕСА ДЛЯ ЗВОРОТНЬОГО ЗВ\'ЯЗКУ'))
            ->add('title', 'text', array('label' => 'ТЕМА ЛИСТА'))
            ->add('body', 'textarea', array('label' => 'ТЕКСТ ПОВІДОМЛЕННЯ', 'attr' => array('rows' => '20')))
            ->add('captcha', 'captcha', array('label' => 'КАПЧА'))
            ->add('Надіслати', 'submit')
            ->getForm();

        return $form->createView();
    }
}