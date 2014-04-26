<?php
/**
 * Created by PhpStorm.
 * File: DreamController.php
 * User: Yuriy Tarnavskiy
 * Date: 11.02.14
 * Time: 11:54
 */

namespace Geekhub\DreamBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\EquipmentContribute;
use Geekhub\DreamBundle\Entity\FinancialContribute;
use Geekhub\DreamBundle\Entity\OtherContribute;
use Geekhub\DreamBundle\Entity\Status;
use Geekhub\DreamBundle\Entity\WorkContribute;
use Geekhub\DreamBundle\Form\DreamCompletedType;
use Geekhub\DreamBundle\Form\DreamImplementingType;
use Geekhub\DreamBundle\Form\DreamRejectType;
use Geekhub\DreamBundle\Form\DreamType;
use FOS\RestBundle\Controller\Annotations\View;
use Geekhub\DreamBundle\Form\EquipmentContributeType;
use Geekhub\DreamBundle\Form\FinancialContributeType;
use Geekhub\DreamBundle\Form\OtherContributeType;
use Geekhub\DreamBundle\Form\WorkContributeType;
use Geekhub\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Constraints\DateTime;

class DreamController extends Controller
{
    /**
     * @View(templateVar="form")
     */
    public function newDreamAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('_login'));
        }

        $dream = new Dream();

        $form = $this->createForm(new DreamType(), $dream, array(
            'dream' => $dream,
            'media-manager' => $this->get('sonata.media.manager.media')
        ));

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $tags = $dream->getTags();
                if (is_null($tags[0])) {
                    $em->persist($dream);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add(
                        'dreamMessage',
                        'Мрія успішно створена.'
                    );
                    return $this->redirect($this->generateUrl('geekhub_dream_homepage'));
                }

                $tagManager = $this->get('geekhub.tag.tag_manager');
                $tagManager->addTagsToEntity($dream);

                $em->persist($dream);
                $em->flush();

                $tagManager->saveTagging($dream);

                $this->get('session')->getFlashBag()->add(
                    'dreamMessage',
                    'Мрія успішно створена.'
                );

                return $this->redirect($this->generateUrl('geekhub_dream_homepage'));
            }
        }

        return $form->createView();
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     * @View()
     */
    public function editDreamAction(Dream $dream, Request $request)
    {
        if (($this->isAuthor($dream)) and ($this->isSuperAdmin())) {
            throw new AccessDeniedException();
        }

        if ((!$this->isAuthor($dream)) and (($dream->getCurrentStatus() == Status::SUCCESS) or ($dream->getCurrentStatus() == Status::FAIL))) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(new DreamType(), $dream, array(
            'dream' => $dream,
            'media-manager' => $this->get('sonata.media.manager.media')
        ));

        $rejectForm = $this->createForm(new DreamRejectType(), $dream);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                if (!$this->isAuthor($dream) and $dream->getCurrentStatus() == Status::REJECTED) {
                    $dream->addStatus(new Status(Status::SUBMITTED));
                    $dream->setRejectedDescription(null);
                }
                $em = $this->getDoctrine()->getManager();
                $tags = $dream->getTags();
                if (is_null($tags[0])) {
                    $em->flush();

                    $this->get('session')->getFlashBag()->add(
                        'dreamMessage',
                        'Мрія відредагована.'
                    );
                    return $this->redirect($this->generateUrl('geekhub_dream_homepage'));
                }
                
                $tagManager = $this->get('geekhub.tag.tag_manager');
                $tagManager->addTagsToEntity($dream);

                $em->flush();

                $tagManager->saveTagging($dream);

                $this->get('session')->getFlashBag()->add(
                    'dreamMessage',
                    'Мрія відредагована.'
                );
                return $this->redirect($this->generateUrl('geekhub_dream_homepage'));
            }
        }

        return array(
            'form'          => $form->createView(),
            'status'        =>$dream->getCurrentStatus(),
            'slug'          =>$dream->getSlug(),
            'rejectDescription' =>$dream->getRejectedDescription(),
            'poster'        => $dream->getMediaPoster(),
            'dreamPictures' => $dream->getMediaPictures(),
            'dreamCompletedPictures' => $dream->getMediaCompletedPictures(),
            'dreamFiles'    => $dream->getMediaFiles(),
            'dreamVideos'   => $dream->getMediaVideos(),
            'rejectForm'    =>$rejectForm->createView()
        );
    }

    /**
     * @View()
     */
    public function listAction()
    {
        return;
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     * @View(templateVar="dream, finForm, equipForm, workForm")
     */
    public function viewDreamAction(Dream $dream, Request $request)
    {
        $user = $this->getUser();
        $financialContribute = new FinancialContribute();
        $equipmentContribute = new EquipmentContribute();
        $workContribute = new WorkContribute();
        $otherContribute = new OtherContribute();
        $finForm = $this->createForm(new FinancialContributeType(), $financialContribute, array('dream' => $dream));
        $equipForm = $this->createForm(new EquipmentContributeType(), $equipmentContribute, array('dream' => $dream));
        $workForm = $this->createForm(new WorkContributeType(), $workContribute, array('dream' => $dream));
        $otherForm = $this->createForm(new OtherContributeType(), $otherContribute);
        $implementingForm = $this->createForm(new DreamImplementingType(), $dream);
        $completedDreamForm = $this->createForm(new DreamCompletedType(), $dream, array(
            'dream' => $dream,
            'media-manager' => $this->get('sonata.media.manager.media')
        ));
        $contributors = $this->getDoctrine()->getRepository('GeekhubDreamBundle:Dream')->getArrayContributorsByDream($dream);

        if ($request->isMethod('POST')) {

            $this->handleContributionForms(
                $request,
                $dream,
                $user,
                $finForm,
                $equipForm,
                $workForm,
                $otherForm,
                $financialContribute,
                $equipmentContribute,
                $workContribute,
                $otherContribute
            );

            return $this->redirect($this->generateUrl('view_dream', array(
                'slug' => $dream->getSlug()
            )));
        }

        return array(
            'dream' => $dream,
            'finForm' => $finForm->createView(),
            'equipForm' => $equipForm->createView(),
            'workForm' => $workForm->createView(),
            'otherForm' => $otherForm->createView(),
            'implementingForm' => $implementingForm->createView(),
            'completedForm' => $completedDreamForm->createView(),
            'contributors' => $contributors,
            'admin_email' => $this->container->getParameter('admin.mail')
        );
    }

    private function handleContributionForms(Request $request, Dream $dream, User $user, Form $finForm,
                                             Form $equipForm, Form $workForm, Form $otherForm,
                                             FinancialContribute $financialContribute,
                                             EquipmentContribute $equipmentContribute,
                                             WorkContribute $workContribute,
                                             OtherContribute $otherContribute)
    {
        if ($request->get('financialContributeForm')) {
            $finForm->handleRequest($request);
            if ($finForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $financialContribute->setDream($dream);
                $financialContribute->setUser($user);
                $em->persist($financialContribute);
                $em->flush();
            }
        }
        if ($request->get('equipmentContributeForm')) {
            $equipForm->handleRequest($request);
            if ($equipForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $equipmentContribute->setDream($dream);
                $equipmentContribute->setUser($user);
                $em->persist($equipmentContribute);
                $em->flush();
            }
        }
        if ($request->get('workContributeForm')) {
            $workForm->handleRequest($request);
            if ($workForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $workContribute->setDream($dream);
                $workContribute->setUser($user);
                $em->persist($workContribute);
                $em->flush();
            }
        }
        if ($request->get('otherContributeForm')) {
            $otherForm->handleRequest($request);
            if ($otherForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $otherContribute->setDream($dream);
                $otherContribute->setUser($user);
                $em->persist($otherContribute);
                $em->flush();
            }
        }
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function removeSomeContributeAction(Dream $dream)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $financialContr = $em->getRepository('GeekhubDreamBundle:Dream')->getFinContr($dream, $user);
        $equipContr = $em->getRepository('GeekhubDreamBundle:Dream')->getEquipContr($dream, $user);
        $workContr = $em->getRepository('GeekhubDreamBundle:Dream')->getWorkContr($dream, $user);

        foreach($financialContr as $finC ) {
            $dream->removeDreamFinancialContribution($finC);
        }

        foreach($equipContr as $equipC) {
            $dream->removeDreamEquipmentContribution($equipC);
        }

        foreach($workContr as $workC) {
            $dream->removeDreamWorkContribution($workC);
        }

        $em->persist($dream);
        $em->flush();

        return $this->redirect($this->generateUrl('view_dream', array(
            'slug' => $dream->getSlug()
        )));
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function rejectDreamAction(Dream $dream, Request $request)
    {
        if ($this->isSuperAdmin()) {
            throw new AccessDeniedException();
        }
        $form = $this->createForm(new DreamRejectType(), $dream);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $dream->addStatus(new Status(Status::REJECTED));
                $em->flush();

                return $this->redirect($this->generateUrl('admin_geekhub_dream_dream_list'));
            }
        }

        return $this->redirect($this->generateUrl('admin_geekhub_dream_dream_list'));
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function confirmDreamAction(Dream $dream, Request $request)
    {
        if ($this->isSuperAdmin()) {
            throw new AccessDeniedException();
        }
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $dream->addStatus(new Status(Status::COLLECTING_RESOURCES));
            $dream->setRejectedDescription(null);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_geekhub_dream_dream_list'));
        }

        return $this->redirect($this->generateUrl('admin_geekhub_dream_dream_list'));
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function implementingDreamAction(Dream $dream, Request $request)
    {
        if ($this->isAuthor($dream)) {
            throw new AccessDeniedException();
        }
        $form = $this->createForm(new DreamImplementingType(), $dream);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $dream->addStatus(new Status(Status::IMPLEMENTING));
                $em->flush();

                return $this->redirect($this->generateUrl('view_dream', array(
                    'slug' => $dream->getSlug()
                )));
            }
        }

        return $this->redirect($this->generateUrl('geekhub_dream_homepage'));
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function editInformationDreamAction(Dream $dream, Request $request)
    {
        $form = $this->createForm(new DreamImplementingType(), $dream);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return $this->redirect($this->generateUrl('view_dream', array(
                    'slug' => $dream->getSlug()
                )));
            }
        }

        return $this->redirect($this->generateUrl('geekhub_dream_homepage'));
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function completingDreamAction(Dream $dream, Request $request)
    {
        if ($this->isAuthor($dream)) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(new DreamCompletedType(), $dream, array(
            'dream' => $dream,
            'media-manager' => $this->get('sonata.media.manager.media')
        ));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $dream->addStatus(new Status(Status::COMPLETED));
                $em->flush();

                return $this->redirect($this->generateUrl('view_dream', array(
                    'slug' => $dream->getSlug()
                )));
            }
        }

        return $this->redirect($this->generateUrl('geekhub_dream_homepage'));
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function successDreamAction(Dream $dream, Request $request)
    {
        if ($this->isSuperAdmin()) {
            throw new AccessDeniedException();
        }
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $dream->addStatus(new Status(Status::SUCCESS));
            $em->flush();

            return $this->redirect($this->generateUrl('admin_geekhub_dream_dream_list'));
        }

        return $this->redirect($this->generateUrl('admin_geekhub_dream_dream_list'));
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function failDreamAction(Dream $dream, Request $request)
    {
        if ($this->isSuperAdmin()) {
            throw new AccessDeniedException();
        }
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $dream->addStatus(new Status(Status::FAIL));
            $em->flush();

            return $this->redirect($this->generateUrl('admin_geekhub_dream_dream_list'));
        }

        return $this->redirect($this->generateUrl('admin_geekhub_dream_dream_list'));
    }

    public function searchAction(Request $request)
    {
        $searchingText = strip_tags(trim($request->get('search_text')));

        if ($searchingText == null or $searchingText == ' ')
            return $this->redirect($this->generateUrl('geekhub_dream_homepage'));

        return $this->redirect($this->generateUrl('dream_search_text', array('text' => $searchingText)));
    }

    /**
     * @View()
     */
    public function searchDreamAction($text)
    {
        if ($text != 'default-search-text') {
            $dreams = $this->getDoctrine()->getRepository('GeekhubDreamBundle:Dream')->searchDreams($text);

            return array(
                'dreams' => $dreams,
                'search_text' => $text
            );
        } else {
            return $this->redirect($this->generateUrl('geekhub_dream_homepage'));
        }
    }

    /**
     * @View(template="GeekhubDreamBundle:Dream:searchDream.html.twig")
     */
    public function dreamsByTagAction($tag)
    {
        if ($tag != 'default-tag') {
            $ids = $this->getDoctrine()->getRepository('TagBundle:Tag')->getResourceIdsForTag('dream_tag', strip_tags(trim($tag)));
            $dreams = new ArrayCollection();
            if (count($ids) > 0 ) {
                foreach ($ids as $id) {
                    $dream = $this->getDoctrine()->getRepository('GeekhubDreamBundle:Dream')->findOneBy(array(
                        'id' => $id,
                        'currentStatus' => array(Status::COLLECTING_RESOURCES, Status::IMPLEMENTING, Status::SUCCESS)
                    ));
                    is_null($dream) ? : $dreams->add($dream);
                }
            }

            return array(
                'dreams' => $dreams,
                'search_text' => $tag
            );
        } else {
            return $this->redirect($this->generateUrl('geekhub_dream_homepage'));
        }
    }

    private function isAuthor(Dream $dream)
    {
        return $this->getUser()->getId() != $dream->getAuthor()->getId() ? true : false;
    }

    private function isSuperAdmin()
    {
        return false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN') ? true : false;
    }
}
