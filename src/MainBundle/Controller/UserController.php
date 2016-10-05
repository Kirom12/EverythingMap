<?php

namespace MainBundle\Controller;

use MainBundle\Entity\User;
use MainBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class UserController extends Controller
{
    public function indexAction()
    {
        return $this->render('MainBundle:User:index.html.twig');
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
