<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Post;
use MainBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    public function addAction(Request $request)
    {

        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            //$validator = $this->get("validator");
            //$errors = $validator->validate($post);

            $type = $post->getType();

            switch ($type) {
                case 'link';
                    //Set non needed values empty on other proprety to be sure
                    $post->setContent('');
                    $post->setImageFile('');

                    if (empty($post->getLink())) {
                        $error = new FormError("Link should not be blank");
                        $form->get('link')->addError($error);
                    }

                    break;
                case 'picture':
                    //Non needed values
                    $post->setContent('');

                    $file = $post->getImageFile();
                    $url = $post->getLink();
                    $imageName = uniqid().'.jpg';

                    if (!is_null($file)) { //If url image, priority is on upload
                        $file->move('library/posts', $imageName);
                    } elseif(!empty($url)) {
                        $file = file_get_contents($url);
                        file_put_contents('library/posts/'. $imageName, $file);
                    } else {
                        $error = new FormError("No image");
                        $form->get('imageFile')->addError($error);
                    }

                    $post->setImageUrl('library/posts/' . $imageName);
                    break;
                case 'text':
                    //Non needed values
                    $post->setImageFile('');
                    $post->setLink('');
                    $post->setCaption('');

                    if (empty($post->getContent())) {
                        $error = new FormError("Content should not be blank");
                        $form->get('content')->addError($error);
                    }
                    break;
                case 'video':
                    //Non needed values
                    $post->setContent('');
                    $post->setImageFile('');

                    if (empty($post->getLink())) {
                        $error = new FormError("Link should not be blank");
                        $form->get('link')->addError($error);
                    }
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

            if($form->isValid()){
                //Save post in DB
                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();

                $this->addFlash('success', 'Post Added');

                return $this->redirectToRoute('main_homepage');
            }
        }

        return $this->render('MainBundle:Post:add.html.twig', array(
            'form'=>$form->createView(),
        ));

    }

}
