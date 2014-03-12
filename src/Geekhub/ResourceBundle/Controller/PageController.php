<?php

namespace Geekhub\ResourceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View;

class PageController extends Controller
{
    /**
     * @View(templateVar = "form")
     */
    public function contactsAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('to', 'email', array('label' => 'АДРЕСА ДЛЯ ЗВОРОТНЬОГО ЗВ\'ЯЗКУ'))
            ->add('title', 'text', array('label' => 'ТЕМА ЛИСТА'))
            ->add('body', 'textarea', array('label' => 'ТЕКСТ ПОВІДОМЛЕННЯ', 'attr' => array('rows' => '15')))
            ->add('captcha', 'captcha', array('label' => 'КАПЧА'))
            ->add('Надіслати', 'submit')
            ->getForm();

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Mail sended with success!'
                );
            }
        }

        return $form->createView();
    }
}
