<?php

namespace MainBundle\Controller;

use MainBundle\Entity\User;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormError;
use MainBundle\Form\EditProfileImageType;
use MainBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function loginAction()
    {
        // TODO: Read https://symfony.com/doc/current/components/security/authentication.html

        //Security: http://symfony.com/doc/current/security.html
        //Login form: http://symfony.com/doc/current/security/form_login_setup.html
        $this->checkAuth("main_homepage");

        // User checkers: http://symfony.com/doc/current/security/user_checkers.html

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('MainBundle:User:login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    public function profileAction($id = 0, $page = 1, Request $request)
    {
        //Pagination : http://www.aubm.net/blog/la-pagination-avec-doctrine-la-bonne-methode/
        // & http://www.christophe-meneses.fr/article/creer-une-pagination-sur-un-projet-symfony
        $nbPostPage = 9;
        $loggedUser = true;

        $user = $this->getUser();

        if ($id != 0 && $id != $user->getId()) { // The current user is not the logged user
            $loggedUser = false;
            $user = $this->getDoctrine()->getRepository("MainBundle:User")->find($id); // Get the profil from other user

            if (!$user) { throw new NotFoundHttpException('Page not found'); }
        }

        $pg = $this->getDoctrine()->getRepository('MainBundle:Post')->getUserPosts($user->getId(), $page, $nbPostPage);

        $userPosts = $pg->getQuery()->getResult();

        $pagination = array(
            'page' => $page,
            'nbPages' => ceil($pg->count() / $nbPostPage),
            'nomRoute' => 'main_user_profile_page',
            'paramsRoute' => array(
                'id' => $user->getId()
            )
        );

        // The user is the logged user
        if ($loggedUser) {
            $imageForm = $this->createForm(EditProfileImageType::class, $user, array('method' => 'PUT'));

            $imageForm->handleRequest($request);

            if($imageForm->isSubmitted()) {
                $file = $user->getImageFile();
                $url = $user->getImageUrl();
                $user->setImageUrl(NULL);
                $imageName = uniqid();

                if (!is_null($file)) { //If upload image (priority is on upload)
                    $imageName .= '.' . $file->guessExtension();
                } elseif (!empty($url)) { // url image
                    try {
                        $file = $this->get('app.url_uploader')->getImageFromUrl($url, $imageName, 1024);

                        $user->setImageFile($file);

                        //Check file is image and dimension
                        $validator = $this->get('validator');
                        $errors = $validator->validate($user);
                        //Throw error if not good
                        if (count($errors) > 0) {
                            foreach ($errors as $error) {
                                $error = new FormError($error->getMessage());
                                $imageForm->get('imageFile')->addError($error);
                            }

                            // Delete the temp file
                            unlink('library/tmp/' . $imageName);
                            throw new Exception();
                        }

                        $imageName .= '.' . $file->guessExtension();
                        $upload = true;
                    } catch (Exception $e) {
                        if (!empty($e->getMessage())) {
                            $error = new FormError($e->getMessage());
                            $imageForm->get('imageFile')->addError($error);
                        }
                    }
                }  else { // Not necessary ? (asserts)
                    $error = new FormError("No image");
                    $imageForm->get('imageFile')->addError($error);
                }
            }

            if($imageForm->isValid()){
                // TODO: delete old image

                $file->move('library/profile_image/', $imageName);
                $user->setImageUrl('library/profile_image/'.$imageName);

                $user->setImageFile(null);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }

            return $this->render('MainBundle:User:profile.html.twig', array(
                'userPosts' => $userPosts,
                'pagination' => $pagination,
                'imageForm' => $imageForm->createView()
            ));
        } else { // The user is not the logged user
            return $this->render('MainBundle:User:userProfile.html.twig', array(
                'user' => $user,
                'userPosts' => $userPosts,
                'pagination' => $pagination
            ));
        }


    }

    public function registerAction(Request $request)
    {
        $this->checkAuth("main_homepage");

        //http://symfony.com/doc/current/doctrine/registration_form.html
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
//            $validator = $this->get("validator");
//            $errors = $validator->validate($user);

            $id = uniqid();
            $user = $user->setSalt($id);
            $user->addRoles('ROLE_USER');
            $encodedPassword = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);
            $user->setMailCheck(uniqid().uniqid());

            $user->setCreatedDate(new \DateTime());

            //Database : http://symfony.com/doc/current/doctrine.html
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // Send email: http://symfony.com/doc/current/email.html
            $message = \Swift_Message::newInstance()
                ->setSubject('Inscription confirmation')
                ->setFrom('everythingmap.dev@gmail.com')
                ->setTo($user->getMail())
                ->setBody(
                    $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                        'Emails/confirmation.html.twig',
                        array(
                            'mailCheck' => $user->getMailCheck(),
                            'userId' => $user->getId()
                        )
                    ),
                    'text/html'
                )
                /*
                 * If you also want to include a plaintext version of the message
                ->addPart(
                    $this->renderView(
                        'Emails/registration.txt.twig',
                        array('name' => $name)
                    ),
                    'text/plain'
                )
                */
            ;
            $this->get('mailer')->send($message);

            // Session management: https://symfony.com/doc/current/components/http_foundation/sessions.html
            // Flash message: https://symfony.com/doc/current/controller.html#flash-messages
            $this->addFlash('success', 'Confirm your account before log in.');

            return $this->redirectToRoute('login');
        }

        return $this->render('MainBundle:User:register.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function checkRegistrationAction($check, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository("MainBundle:User")->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '. $id
            );
        } else if (!$user->getValid()) {
            if($user->getMailCheck() === $check) {
                $user->setValid(true);
                $em->flush();

                $this->addFlash('success', 'Inscription confirmed. Now log in bitch!');
            } else {
                $this->addFlash('error', 'Error during the confirmation. Check the link');
            }
        } else {
            $this->addFlash('error', 'Your already confirm your account.');
        }

        return $this->redirectToRoute('login');
    }

    private function checkAuth($route)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute($route);
        }
    }
}
