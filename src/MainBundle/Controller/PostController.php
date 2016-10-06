<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Post;
use MainBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    public function indexAction()
    {
        return $this->render('MainBundle:Post:index.html.twig');
    }

    public function addAction( Request $request)
    {

        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if($form->isValid()){
            $validator = $this->get("validator");
            $errors = $validator->validate($post);

            $type = $post->getType();

            switch ($type) {
                case 'link';
                    break;
                case 'picture':
                    $file = $post->getImageFile();
                    $image_name = uniqid().'.jpg';
                    $file->move('library/posts', $image_name);
                    $post->setImageUrl('library/posts/' . $image_name);
                    break;
                case 'text':
                    break;
                case 'video':
                    break;
                default:
                    // TODO: incorrect type message
            }

            //Common to every posts
            $post->setCreationDate(new \DateTime());

            //Set user to post if user is authenticated
            if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                $post->setUser($this->getUser());
            }

            //Save post in DB
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

        }
        return $this->render('MainBundle:Post:add.html.twig', array(
            'form'=>$form->createView(),
        ));

    }

}
