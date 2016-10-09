<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Post;
use MainBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    public function addAction(Request $request)
    {
        // TODO: refactor this shit
        $upload = false;

        $this->get('doctrine');
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
                    $post->setContent(NULL);
                    $post->setImageFile(NULL);

                    if (empty($post->getLink())) {
                        $error = new FormError("Link should not be blank");
                        $form->get('link')->addError($error);
                    }

                    break;
                case 'picture':
                    //Non needed values
                    $post->setContent(NULL);

                    $file = $post->getImageFile();
                    $url = $post->getLink();
                    $imageName = uniqid();

                    if (!is_null($file)) { //If upload image (priority is on upload)
                        $upload = true;
                        $imageName .= '.'.$file->guessExtension();
                    } elseif(!empty($url)) { // url image
                        try {
                            $file = $this->get('app.url_uploader')->getImageFromUrl($url, $imageName, 1024);

                            $post->setImageFile($file);

                            //Check file is image and dimension
                            $validator = $this->get('validator');
                            $errors = $validator->validate($post);
                            //Throw error if not good
                            if (count($errors) > 0) {
                                foreach ($errors as $error) {
                                    $error = new FormError($error->getMessage());
                                    $form->get('imageFile')->addError($error);
                                }
                                // Delete the temp file
                                unlink('library/tmp/'.$imageName);
                                throw new Exception();
                            }

                            $imageName .= '.'.$file->guessExtension();
                            $upload = true;
                        } catch (Exception $e) {
                            if (!empty($e->getMessage())) {
                                $error = new FormError($e->getMessage());
                                $form->get('imageFile')->addError($error);
                            }
                        }
                    } else { // Not necessary ? (asserts)
                        $error = new FormError("No image");
                        $form->get('imageFile')->addError($error);
                    }

                    $post->setImageUrl('library/posts/' . $imageName);
                    break;
                case 'text':
                    $post->setImageFile(NULL);
                    $post->setLink(NULL);
                    $post->setCaption(NULL);

                    if (empty($post->getContent())) {
                        $error = new FormError("Content should not be blank");
                        $form->get('content')->addError($error);
                    }
                    break;
                case 'video':
                    $post->setContent(NULL);
                    $post->setImageFile(NULL);

                    if (empty($post->getLink())) {
                        $error = new FormError("Link should not be blank");
                        $form->get('link')->addError($error);
                    }

                    $parts = parse_url($post->getLink());


                    try {
                        if ($parts['host'] == "www.youtube.com" || $parts['host'] == "youtube.com") {
                            $parts = parse_url($post->getLink());
                            parse_str($parts['query'], $query);
                            if (!isset($query['v'])) {
                                throw new Exception();
                            }
                            $videoId = $query['v'];
                            $post->setLink($videoId);
                        } else {
                            throw new Exception();
                        }
                    } catch (Exception $e) {
                        $error = new FormError("Video link invalid. It should be something like : https://www.youtube.com/watch?v=dQw4w9WgXcQ");
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
                if ($upload) {
                    $file->move('library/posts', $imageName);
                }

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
