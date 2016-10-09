<?php

namespace MainBundle\Controller;

use MainBundle\Entity\User;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormError;
use MainBundle\Form\EditProfileImageType;
use MainBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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


    public function profileAction(Request $request)
    {
        $user = $this->getUser();

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
            'imageForm' => $imageForm->createView()
        ));
    }

    public function profileByIdAction($id)
    {
        $user = $this->getDoctrine()->getRepository("MainBundle:User")->find($id);

        return $this->render('MainBundle:User:userProfile.html.twig', array(
            'user' =>$user
        ));
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
