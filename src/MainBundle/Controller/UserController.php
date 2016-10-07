<?php

namespace MainBundle\Controller;

use MainBundle\Entity\User;
use MainBundle\Form\EditProfileImageType;
use MainBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserController extends Controller
{
    public function loginAction()
    {
        //Security: http://symfony.com/doc/current/security.html
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('main_homepage');
        }

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

        if($imageForm->isValid()){
            //dump($user);die;
            // http://symfony.com/doc/current/controller/upload_file.html
            // $file stores the uploaded PDF file

            $file = $user->getImageFile();

            //dump($file);
            //die;
            //$url = $user->getImageUrl();

            $imageName = uniqid().'.jpg';
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
        $user = $this->getDoctrine()->getRepository("MainBundle:User")->find ($id);

        return $this->render('MainBundle:User:userProfile.html.twig', array(
            'user' =>$user

        ));

    }

    public function registerAction(Request $request)
    {
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
            $user->setCreatedDate(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // Session management: https://symfony.com/doc/current/components/http_foundation/sessions.html
            // Flash message: https://symfony.com/doc/current/controller.html#flash-messages
            $this->addFlash('success', 'Inscription confirmed. Now log in bitch!');

            return $this->redirectToRoute('login');
        }

        return $this->render('MainBundle:User:register.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
