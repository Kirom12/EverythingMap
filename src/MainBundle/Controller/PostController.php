<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostController extends Controller
{
    public function indexAction()
    {
        return $this->render('MainBundle:Post:index.html.twig');
    }

    public function addAction()
    {
        return $this->render('MainBundle:Post:add.html.twig');
    }
}
