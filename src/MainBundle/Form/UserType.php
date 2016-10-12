<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
                    'label'=> 'Username',
                    'required'=> false,
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
                        'label' => 'Password',
                        'error_bubbling' => true,
                        ),
                    'second_options' => array(
                        'label' => 'Repeat Password',
                    ),
                ))
            ->add('firstName', TextType::class,
                array(
                    'label'=> 'First name',
                    'required'=> false,
                ))

            ->add('lastName', TextType::class,
                array(
                    'label'=> 'Last name',
                    'required'=> false,
                ))
            ->add('mail', TextType::class, array(
                'label'=> 'Mail',
                'required'=> false,
            ))

            ->add('submit', SubmitType::class, array(
                'label'=>'Get Started',
                'attr' => array(
                    'class' => 'button button-block'
                )
            ));
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
