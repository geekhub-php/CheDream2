<?php

namespace Geekhub\DreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GeekhubDreamBundle:Default:index.html.twig');
    }

    public function contactAction()
    {
        $form = $this->createFormBuilder()
            ->add('to', 'email', array('label' => 'АДРЕСА ДЛЯ ЗВОРОТНЬОГО ЗВ\'ЯЗКУ'))
            ->add('title', 'text', array('label' => 'ТЕМА ЛИСТА'))
            ->add('body', 'textarea', array('label' => 'ТЕКСТ ПОВІДОМЛЕННЯ'))
            ->add('captcha', 'text', array('label' => 'КАПЧА'))
            ->add('Надіслати', 'submit')
            ->getForm();

        return $this->render('GeekhubDreamBundle:Default:contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
