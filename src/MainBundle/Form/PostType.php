<?php

namespace MainBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,
                array(
                    'label'=> 'Title',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-lg-2 control-label'
                    ),
                    'attr'=>array(
                        'class'=>'form-control'
                    )
                ))

            ->add('caption', TextType::class,
                array(
                    'label'=> 'Caption',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-lg-2 control-label'
                    ),
                    'attr'=>array(
                        'class'=>'form-control'
                    )
                ))
            ->add('link', TextType::class,
                array(
                    'label'=> 'Link',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-lg-2 control-label'
                    ),
                    'attr'=>array(
                        'class'=>'form-control'
                    )
                ))
            ->add('content', TextareaType::class,
                array(
                    'label'=> 'Content',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-lg-2 control-label'
                    ),
                    'attr'=>array(
                        'class'=>'form-control'
                    )
                ))
            ->add('type', TextType::class,
                array(
                    'label'=> 'Type',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-lg-2 control-label'
                    ),
                    'attr'=>array(
                        'class'=>'form-control'
                    )
                ))
            ->add('categories', EntityType::class,
                array(
                    'label'=> 'Categories',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-lg-2 control-label'
                    ),
                    'class' => 'MainBundle:Category',
                    'attr'=>array(
                        'class'=>'form-control'
                    )
                ))
            ->add('tags', TextType::class,
                array(
                    'label'=> 'Tags',
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
            ));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Post'
        ));
    }
}
