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
        $posts = $this->getDoctrine()->getRepository('MainBundle:Post')->findBy(
            array(),
            array('id' => 'DESC')
        );

        return $this->render('MainBundle:Default:index.html.twig', array(
        'compteur'=>count($posts),
        'posts'=>$posts));
    }


    public function indexUserAction()
    {
        return $this->render('MainBundle:User:index.html.twig');
    }

    public function indexAdminAction($page = 1, $page2 = 1)
    {
        $nbPostPage = 50;
        $nbUserPage = 50;

        $pg = $this->getDoctrine()->getRepository('MainBundle:Post')->getPostsPage(NULL, $page, $nbPostPage);
        $secondPg = $this->getDoctrine()->getRepository('MainBundle:User')->getUsersPage(NULL, $page2, $nbUserPage);

        $posts = $pg->getQuery()->getResult();
        $users = $secondPg->getQuery()->getResult();

        // C'est sale !
        $pagination = array(
            'page' => $page,
            'nbPages' => ceil($pg->count() / $nbPostPage),
            'nomRoute' => 'main_admin_page',
            'paramsRoute' => array(
                'page2' => $page2
            )
        );
        $secondPagination = array(
            'page' => $page2,
            'nbPages' => ceil($secondPg->count() / $nbUserPage),
            'nomRoute' => 'main_admin_page',
            'paramsRoute' => array(
                'page' => $page
            )
        );

        return $this->render('MainBundle:Admin:index.html.twig', array(
            'posts' => $posts,
            'users' => $users,
            'pagination' => $pagination,
            'secondPagination' => $secondPagination
        ));
    }

}
