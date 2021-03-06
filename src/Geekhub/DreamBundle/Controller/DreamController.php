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
                $em->persist($dream);

                $tags = $dream->getTags();
                if (is_null($tags[0])) {
                    $tagManager = $this->get('geekhub.tag.tag_manager');
                    $tagManager->addTagsToEntity($dream);

                    $em->flush();

                    $tagManager->saveTagging($dream);
                }

                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'dreamMessage',
                    'Мрія успішно створена.'
                );

                return $this->redirect($this->generateUrl('view_dream', ['slug' => $dream->getSlug()]));
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
        if (false === $this->canEditDream($dream)) {
            throw new AccessDeniedException('Мрію дозволено редагувати лише автору та адміністратору');
        }

        if (false === $this->isSuperAdmin() && true === $this->isCloseDream($dream)) {
            throw new AccessDeniedException('Мрія завершена - ви не можете більше редагувати мрію. Зверніться будь ласка до адміністратора');
        }

        if (false === $this->isSuperAdmin() && false === $this->isDraftDream($dream)) {
            throw new AccessDeniedException('Ви не можете більше редагувати мрію. Зверніться будь ласка до адміністратора');
        }

        $form = $this->createForm(new DreamType(), $dream, array(
            'dream' => $dream,
            'media-manager' => $this->get('sonata.media.manager.media')
        ));

        $rejectForm = $this->createForm(new DreamRejectType(), $dream);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                if (true === $this->isAuthor($dream) && $dream->getCurrentStatus() == Status::REJECTED) {
                    $dream->addStatus(new Status(Status::SUBMITTED));
                    $dream->setRejectedDescription(null);
                }
                $em = $this->getDoctrine()->getManager();
                $tags = $dream->getTags();

                if (false === is_null($tags[0])) {
                    $tagManager = $this->get('geekhub.tag.tag_manager');
                    $tagManager->addTagsToEntity($dream);

                    $em->flush();

                    $tagManager->saveTagging($dream);
                }

                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'dreamMessage',
                    'Мрія відредагована.'
                );

                return $this->redirect($this->generateUrl('view_dream', ['slug' => $dream->getSlug()]));
            }
        }

        return array(
            'form' => $form->createView(),
            'status' => $dream->getCurrentStatus(),
            'slug' => $dream->getSlug(),
            'rejectDescription' => $dream->getRejectedDescription(),
            'poster' => $dream->getMediaPoster(),
            'dreamPictures' => $dream->getMediaPictures(),
            'dreamCompletedPictures' => $dream->getMediaCompletedPictures(),
            'dreamFiles' => $dream->getMediaFiles(),
            'dreamVideos' => $dream->getMediaVideos(),
            'rejectForm' => $rejectForm->createView()
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

        if ($this->isDraftDream($dream) && false === $this->canEditDream($dream)) {
            $this->get('session')->getFlashBag()->add(
                'dreamMessage',
                'Мрія не підтверджена адміністратором'
            );
            return $this->redirect($this->generateUrl('geekhub_dream_homepage'));
        }

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

                if (is_null($user->getPhone())) {
                    $tel = $request->get('contributor-telephone');
                    $user->setPhone($tel);
                    $em->persist($user);
                }

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

                if (is_null($user->getPhone())) {
                    $tel = $request->get('contributor-telephone');
                    $user->setPhone($tel);
                    $em->persist($user);
                }

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

                if (is_null($user->getPhone())) {
                    $tel = $request->get('contributor-telephone');
                    $user->setPhone($tel);
                    $em->persist($user);
                }

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

                if (is_null($user->getPhone())) {
                    $tel = $request->get('contributor-telephone');
                    $user->setPhone($tel);
                    $em->persist($user);
                }

                $otherContribute->setDream($dream);
                $otherContribute->setUser($user);
                $em->persist($otherContribute);
                $em->flush();
            }
        }
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     * @ParamConverter("user", class="GeekhubUserBundle:User")
     */
    public function removeSomeContributeAction(Dream $dream, User $user)
    {
        if (false === $this->canEditDream($dream)) {
            throw new AccessDeniedException('Видаляти контрібути дозволено лише автору та адміністратору');
        }

        $em = $this->getDoctrine()->getManager();

        $financialContributions = $dream->getDreamFinancialContributions()->map($this->getContributionElement($user));
        $equipContributions = $dream->getDreamEquipmentContributions()->map($this->getContributionElement($user));
        $workContributions = $dream->getDreamWorkContributions()->map($this->getContributionElement($user));
        $otherContributions = $dream->getDreamOtherContributions()->map($this->getContributionElement($user));

        foreach ($financialContributions as $financialContribution) {
            if (!is_null($financialContribution)) {
                $em->remove($financialContribution);
            }
        }

        foreach ($equipContributions as $equipContribution) {
            if (!is_null($equipContribution)) {
                $em->remove($equipContribution);
            }
        }

        foreach ($workContributions as $workContribution) {
            if (!is_null($workContribution)) {
                $em->remove($workContribution);
            }
        }

        foreach ($otherContributions as $otherContribution) {
            if (!is_null($otherContribution)) {
                $em->remove($otherContribution);
            }
        }

        $em->flush();

        return $this->redirect($this->generateUrl('view_dream', array(
            'slug' => $dream->getSlug()
        )));
    }

    protected function getContributionElement($user)
    {
        return function ($element) use ($user) {
            if ($element->getUser() == $user) {
                return $element;
            }
        };
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function rejectDreamAction(Dream $dream, Request $request)
    {
        if (false === $this->isSuperAdmin()) {
            throw new AccessDeniedException('Повернути мрію на доопрацювання може лише адміністратор');
        }
        $form = $this->createForm(new DreamRejectType(), $dream);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $dream->addStatus(new Status(Status::REJECTED));
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('view_dream', ['slug' => $dream->getSlug()]));
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function confirmDreamAction(Dream $dream, Request $request)
    {
        if (false === $this->isSuperAdmin()) {
            throw new AccessDeniedException('Помітити мрію як перевірену може тільки адміністратор');
        }
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $dream->addStatus(new Status(Status::COLLECTING_RESOURCES));
            $dream->setRejectedDescription(null);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('view_dream', ['slug' => $dream->getSlug()]));
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function implementingDreamAction(Dream $dream, Request $request)
    {
        if (false === $this->canEditDream($dream)) {
            throw new AccessDeniedException('Перевести мрію у статус виконання може тільки автор мрії або адміністратор');
        }
        $form = $this->createForm(new DreamImplementingType(), $dream);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $dream->addStatus(new Status(Status::IMPLEMENTING));

                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('view_dream', ['slug' => $dream->getSlug()]));
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function editInformationDreamAction(Dream $dream, Request $request)
    {
        if (false === $this->canEditDream($dream)) {
            throw new AccessDeniedException('Редагувати мрію може тільки автор мрії або адміністратор');
        }

        $form = $this->createForm(new DreamImplementingType(), $dream);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('view_dream', ['slug' => $dream->getSlug()]));
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function completingDreamAction(Dream $dream, Request $request)
    {
        if (false === $this->canEditDream($dream)) {
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
            }
        }

        return $this->redirect($this->generateUrl('view_dream', ['slug' => $dream->getSlug()]));
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function successDreamAction(Dream $dream, Request $request)
    {
        if (false === $this->isSuperAdmin()) {
            throw new AccessDeniedException('Підтвердити успішне завершення мрії може тільки адміністратор');
        }
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $dream->addStatus(new Status(Status::SUCCESS));
            $em->flush();
        }

        return $this->redirect($this->generateUrl('view_dream', ['slug' => $dream->getSlug()]));
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function failDreamAction(Dream $dream, Request $request)
    {
        if (false === $this->isSuperAdmin()) {
            throw new AccessDeniedException('Позначити мрію як не виконану може тільки адміністратор');
        }
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $dream->addStatus(new Status(Status::FAIL));
            $em->flush();
        }

        return $this->redirect($this->generateUrl('view_dream', ['slug' => $dream->getSlug()]));
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
            if (count($ids) > 0) {
                foreach ($ids as $id) {
                    $dream = $this->getDoctrine()->getRepository('GeekhubDreamBundle:Dream')->findOneBy(array(
                        'id' => $id,
                        'currentStatus' => array(Status::COLLECTING_RESOURCES, Status::IMPLEMENTING, Status::SUCCESS)
                    ));
                    is_null($dream) ?: $dreams->add($dream);
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
        if (!$this->getUser() || !$dream->getAuthor()) {
            return false;
        }

        return $this->getUser()->getId() == $dream->getAuthor()->getId();
    }

    private function isSuperAdmin()
    {
        return $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
    }

    private function canEditDream(Dream $dream)
    {
        return $this->isAuthor($dream) || $this->isSuperAdmin();
    }

    private function isCloseDream(Dream $dream)
    {
        return in_array($dream->getCurrentStatus(), [Status::SUCCESS, Status::FAIL]);
    }

    private function isDraftDream(Dream $dream)
    {
        return in_array($dream->getCurrentStatus(), [Status::REJECTED, Status::SUBMITTED, Status::COMPLETED]);
    }
}
