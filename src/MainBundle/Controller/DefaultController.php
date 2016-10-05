<?php

namespace MainBundle\Controller;

use MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MainBundle:Default:index.html.twig');
    }

    public function indexUserAction()
    {
        return $this->render('MainBundle:User:index.html.twig');
    }

    public function indexAdminAction()
    {
        return $this->render('MainBundle:Admin:index.html.twig');
    }
}
