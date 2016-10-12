<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class,
                array(
                    'label'=> 'First name',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-md-2 control-label'
                    ),
                    'attr'=>array(
                        'class'=>'form-control'
                    )

                ))
            ->add('lastName', TextType::class,
                array(
                    'label'=> 'Last name',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-md-2 control-label'
                    ),
                    'attr'=>array(
                        'class'=>'form-control'
                    )

                ))
            ->add('mail', TextType::class,
                array(
                    'label'=> 'Mail',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-md-2 control-label'
                    ),
                    'attr'=>array(
                        'class'=>'form-control'
                    )
                ))
            ->add('submit', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-success'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\User'
        ));
    }
}
