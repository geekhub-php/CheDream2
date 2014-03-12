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
            ->add('to', 'email', array('label' => 'contacts.write_us.email'))
            ->add('title', 'text', array('label' => 'contacts.write_us.theme'))
            ->add('body', 'textarea', array('label' => 'contacts.write_us.body', 'attr' => array('rows' => '15')))
            ->add('captcha', 'captcha', array('label' => 'contacts.write_us.captcha'))
            ->add('submit', 'submit', array('label' => 'contacts.write_us.submit'))
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
