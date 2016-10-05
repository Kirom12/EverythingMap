<?php

namespace MainBundle\Controller;

use MainBundle\Entity\User;
use MainBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


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

    public function registerAction(Request $request)
    {
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
            'form'=>$form->createView(),
        ));
    }
}
