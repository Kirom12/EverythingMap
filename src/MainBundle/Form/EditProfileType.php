<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, array(
                'label' => 'Image',
                'label_attr' => array(
                    'class' => 'col-lg-2 control-label'
                )
            ))
            ->add('url', TextType::class, array(
                'label' => 'Url',
                'label_attr' => array(
                    'class' => 'col-lg-2 control-label'
                ),
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'main_bundle_edit_profile_type';
    }
}
