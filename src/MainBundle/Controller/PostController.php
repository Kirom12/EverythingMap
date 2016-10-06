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
            //$validator = $this->get("validator");
            //$errors = $validator->validate($post);

            $type = $post->getType();

            switch ($type) {
                case 'link';
                    //Set non needed values empty on other proprety to be sure
                    $post->setContent('');
                    $post->setImageFile('');

                    break;
                case 'picture':
                    //Non needed values
                    $post->setContent('');

                    $file = $post->getImageFile();
                    $imageName = uniqid().'.jpg';

                    if (is_null($file)) { //If url image, priority is on upload
                        $file = file_get_contents($post->getLink());
                        file_put_contents('library/posts/'. $imageName, $file);
                    } else {
                        $file->move('library/posts', $imageName);
                    }

                    $post->setImageUrl('library/posts/' . $imageName);
                    break;
                case 'text':
                    //Non needed values
                    $post->setImageFile('');
                    $post->setLink('');
                    $post->setCaption('');
                    break;
                case 'video':
                    //Non needed values
                    $post->setContent('');
                    $post->setImageFile('');

                    break;
                default:
                    // TODO: incorrect type message
            }

            //Common to every posts
            $post->setCreationDate(new \DateTime());
            //Set user to post if user is authenticated, else: NULL
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
