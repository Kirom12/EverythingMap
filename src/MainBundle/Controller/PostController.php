<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Post;
use MainBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    public function addAction($id = NULL, Request $request)
    {
        // TODO: refactor this shit
        $upload = false;
        $edition = false;
        $em = $this->getDoctrine()->getManager();

        if ($id) { // Edition of a post
            $user = $this->getUser(); // Get the current user
            $post = $em->getRepository("MainBundle:Post")->find($id);

            //dump($user);die;

            $this->checkAuthPostUser($user, $post);

            if ($post->getType() == 'video') {
                $post->setLink("https://www.youtube.com/watch?v=".$post->getLink());
            }

            $originalType = $post->getType();
            $edition = true;
            $form = $this->createForm(PostType::class, $post, array(
                'method' => 'PUT',
                'edition' => $edition
            ));
        } else { // New post
            $this->get('doctrine'); // why ?
            $post = new Post();
            // Form options: http://stackoverflow.com/questions/25399290/avoid-symfony-forcing-form-fields-display
            $form = $this->createForm(PostType::class, $post, array('edition' => $edition));
        }

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            //$validator = $this->get("validator");
            //$errors = $validator->validate($post);

            $type = $post->getType();

            // Check type hidden has not be modified
            if ($edition && $post->getType() !== $originalType) { throw new Exception('Type modified'); }

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

            if($form->isValid()){
                //**Common to every posts
                //Set user to post if user is authenticated, else: NULL
                if (!$edition) { // New post
                    $post->setCreationDate(new \DateTime());
                    if (!$form->get('anonymous')->getData() && $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                        $post->setUser($this->getUser());
                    }
                } else { // Edition
                    $post->setModificationDate(new \DateTime());
                }
                //**

                if ($upload) {
                    $file->move('library/posts', $imageName);
                }

                //Save post in DB
                $em->persist($post);
                $em->flush();

                if (!$edition) {
                    $this->addFlash('success', 'Post Added');
                    return $this->redirectToRoute('main_homepage');
                } else {
                    $this->addFlash('success', 'Post modified');
                    //TODO modify redirect for edition
                    return $this->redirectToRoute('main_homepage');
                }
            }
        }

        return $this->render('MainBundle:Post:add.html.twig', array(
            'edition' => $edition,
            'form'=>$form->createView()
        ));

    }

    public function deleteAction($id, Request $request) {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository("MainBundle:Post")->find($id);

        $this->checkAuthPostUser($user, $post);

        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Post deleted');

        return $this->redirect($request->headers->get('referer'));
    }

    public function checkAuthPostUser($user, $post) {
        // If The post doesn't exist
        if (!$post) { throw new NotFoundHttpException('Page not found'); }
        // If the user doesn't own the post or is not admin
        if ($post->getUser() !== $user && !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) { throw new NotFoundHttpException('Page not found'); }
    }
}
