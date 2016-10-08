<?php

namespace MainBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            // Doc choice : http://symfony.com/doc/current/reference/forms/types/choice.html
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    '' => '',
                    'Text' => 'text',
                    'Picture' => 'picture',
                    'Youtube Video' => 'video',
                    'Link' => 'link'
                ),
                'label_attr'=>array(
                    'class'=>'col-md-2 control-label'
                ),
                'attr'=>array(
                    'class'=>'form-control',
                )
            ))
            ->add('title', TextType::class,
                array(
                    'label'=> 'Title',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-md-2 control-label'
                    ),
                    'attr'=>array(
                        'class'=>'form-control'
                    )
                ))

            ->add('caption', TextareaType::class,
                array(
                    'label'=> 'Caption',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-md-2 control-label'
                    ),
                    'attr'=>array(
                        'class'=>'form-control'
                    )
                ))
            ->add('link', TextType::class,
                array(
                    'label'=> 'Lien',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-md-2 control-label'
                    ),
                    'attr'=>array(
                        'class'=>'form-control',
                        'placeholder' => 'Link'
                    )
                ))
            ->add('imageFile', FileType::class, array(
                'label' => 'Image',
                'required'=> false,
                'label_attr' => array(
                    'class' => 'control-label'
                )
            ))
            ->add('content', TextareaType::class,
                array(
                    'label'=> 'Content',
                    'required'=> false,
                    'label_attr'=>array(
                        'class'=>'col-md-2 control-label'
                    ),
                    'attr'=>array(
                        'class'=>'form-control'
                    )
                ))
            ->add('category', EntityType::class,
                array(
                    'label'=> 'Category',
                    'required'=> false,
                    //'multiple' => true,
                    'label_attr'=>array(
                        'class'=>'col-md-2 control-label'
                    ),
                    'class' => 'MainBundle:Category',
                    'choice_label'=> 'name',
                    'attr'=>array(
                        'class'=>'form-control',
                    )
                ))
            // TODO: Implements tags validation
//            ->add('tags', TextType::class,
//                array(
//                    'label'=> 'Tags',
//                    'required'=> false,
//                    'label_attr'=>array(
//                        'class'=>'col-lg-2 control-label'
//                    ),
//                    'attr'=>array(
//                        'class'=>'form-control'
//                    )
//                ))
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
