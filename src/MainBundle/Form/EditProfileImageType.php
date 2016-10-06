<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', FileType::class, array(
                'label' => 'Image',
                'required'=> false,
                'label_attr' => array(
                    'class' => 'control-label'
                )
            ))
            ->add('imageUrl', TextType::class, array(
                'label' => 'Url',
                'required'=> false,
                'data' => null,
                'label_attr' => array(
                    'class' => 'control-label'
                ),
                'attr' => array(
                    'class' => 'form-control input-sm',
                    'placeholder' => 'Url'
                )
            ))
            ->add('Save', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-success pull-right'
                )
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'main_bundle_edit_profile_type';
    }
}
