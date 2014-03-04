<?php
/**
 * Created by PhpStorm.
 * File: DreamType.php
 * User: Yuriy Tarnavskiy
 * Date: 11.02.14
 * Time: 11:50
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DreamType extends AbstractType
{
    public function getName()
    {
        return 'newDreamForm';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var \GeekHub\DreamBundle\Entity\Dream $dream */
        $dream = $options['dream'];
        $mediaManager = $options['media-manager'];

        $transformerTag = new TagTransformer();
        $transformerPicture = new DreamPicturesTransformer($dream, $mediaManager);
        $transformerPoster = new DreamPosterTransformer($dream, $mediaManager);
        $transformerFile = new DreamFilesTransformer($dream, $mediaManager);
        $transformerVideo = new DreamVideoTransformer($dream, $mediaManager);

        $builder
            ->add('title', 'text', array('label' => 'назва '))
            ->add('description', 'textarea', array('label' => 'опис '))
            ->add('phone', 'text', array('label' => 'телефон' ))
            ->add($builder->create('tags', 'text', array(
                'attr' => array('class' => 'form-control'),
                'required'  => false,
                'label' => 'Ключові слова'
            ))->addModelTransformer($transformerTag))
            ->add('expiredDate', 'date', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'Кінцева дата: ',
            ))
            ->add('dreamFinancialResources', 'collection', array(
                'type' => new FinancialType(),
//                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'  => false,
            ))
            ->add('dreamEquipmentResources', 'collection', array(
                'type' => new EquipmentType(),
//                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'  => false,
            ))
            ->add('dreamWorkResources', 'collection', array(
                'type' => new WorkType(),
//                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'  => false,
            ))
            ->add($builder->create('dreamPictures', 'hidden')->addModelTransformer($transformerPicture))
            ->add($builder->create('dreamPoster', 'hidden')->addModelTransformer($transformerPoster))
            ->add($builder->create('dreamFiles', 'hidden')->addModelTransformer($transformerFile))
            ->add($builder->create('dreamVideos', 'hidden')->addModelTransformer($transformerVideo));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Geekhub\DreamBundle\Entity\Dream'
            ))
            ->setRequired(array('dream', 'media-manager'))
            ->setAllowedTypes(array(
                'dream' => 'Geekhub\DreamBundle\Entity\Dream',
                'media-manager' => 'Sonata\MediaBundle\Entity\MediaManager'
            ));
    }
}
