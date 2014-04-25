<?php

namespace Geekhub\ResourceBundle\Controller;

use Hip\MandrillBundle\Message;
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
            ->add('body', 'textarea', array(
                    'label' => 'contacts.write_us.body',
                    'attr' => array('rows' => '15')
                ))
            ->add('captcha', 'captcha', array('label' => 'contacts.write_us.captcha'))
            ->setAction($this->generateUrl('page_contacts'))
            ->getForm();

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $dispatcher = $this->container->get('hip_mandrill.dispatcher');

                $message = new Message();

                $message->setFromEmail($form->get('to')->getData())
                    ->setFromName('Черкаська мрія - зворотна форма звязку')
                    ->addTo($this->container->getParameter('admin.mail'))
                    ->setSubject($form->get('title')->getData())
                    ->setHtml($form->get('body')->getData())
                ;

                $dispatcher->send($message);

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Mail sended with success!'
                );
            }
        }

        return $form->createView();
    }

    /**
     * @View()
     */
    public function aboutAction()
    {}
}
