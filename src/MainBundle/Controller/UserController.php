<?php

namespace MainBundle\Controller;

use MainBundle\Entity\User;
use MainBundle\Form\EditProfileType;
use MainBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserController extends Controller
{
    public function loginAction()
    {
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

        $imageForm = $this->createForm(EditProfileType::class, $user);

        $imageForm->handleRequest($request);
        if($imageForm->isSubmitted() && $imageForm->isValid()){
            // http://symfony.com/doc/current/controller/upload_file.html
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $user->getImageFile();

            $url = $user->getImageUrl();

            if (!empty($file)) {
                $imageName = uniqid();
                $file->move('library/profile_image/'.$user->getId().'/', $imageName);
                $user->setImageUrl('library/profile_image/'.$imageName.'.jpg');

            }
        }

        return $this->render('MainBundle:User:profile.html.twig', array(
            'imageForm' => $imageForm->createView()
        ));
    }

    public function registerAction(Request $request)
    {
        //http://symfony.com/doc/current/doctrine/registration_form.html
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isValid()){
            $validator = $this->get("validator");
            $errors = $validator->validate($user);

            $id = uniqid();
            $user = $user->setSalt($id);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->render('MainBundle:User:register.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
