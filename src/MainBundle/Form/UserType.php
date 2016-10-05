<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class,
                array(
                    'label'=> 'Username *',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-lg-2 control-label'
                    ),
                    'attr'=>array(
                        'class'=>'form-control'
                    )
                ))
            ->add('password', RepeatedType::class,
                array(
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => array(
                    'attr' => array(
                       'class' => 'password-field'
                   )),
                    'required' => false,
                    'first_options'  => array(
                    'label' => 'Password *',
                    'label_attr'=>array(
                        'class'=>'col-lg-2 control-label'
                        ),
                    'attr'=>array(
                        'class'=>'form-control'
                        )
                    ),
                    'second_options' => array(
                    'label' => 'Repeat Password *',
                    'label_attr'=>array(
                        'class'=>'col-lg-2 control-label'
                        ),
                    'attr'=>array(
                        'class'=>'form-control'
                        )
                    ),
                ))
            ->add('firstName', TextType::class,
                array(
                    'label'=> 'First name',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-lg-2 control-label'
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
                        'class'=>'col-lg-2 control-label'
                    ),
                    'attr'=>array(
                        'class'=>'form-control'
                    )

                ))
            ->add('mail', TextType::class, array(
                'label'=> 'Mail *',
                'required'=> false,
                'label_attr'=>array(
                    'class'=>'col-lg-2 control-label'
                ),
                'attr'=>array(
                    'class'=>'form-control'
                )

            ))

            ->add('submit', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\User'
        ));
    }
}
