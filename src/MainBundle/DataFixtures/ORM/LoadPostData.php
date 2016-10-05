<?php

namespace MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MainBundle\Entity\Post;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $admin = $this->getReference('admin');
        $user1 = $this->getReference('user1');

        $post = new Post();
        $post->setType("video");
        $post->setUser($admin);
        $post->setTitle("Apologize - One Republic - (Janet Devlin Cover) ");
        $post->setCreationDate(new \DateTime());
        $post->setLink("https://youtu.be/pwILgrLoX_A");
        $manager->persist($post);

        $post = new Post();
        $post->setType("video");
        $post->setUser($user1);
        $post->setTitle("Californication - Red Hot Chili Peppers (Janet Devlin Cover) ");
        $post->setCreationDate(new \DateTime());
        $post->setLink("https://youtu.be/s8m_XEGxm0I");
        $manager->persist($post);

        $post = new Post();
        $post->setType("text");
        $post->setUser($admin);
        $post->setTitle("Lorem ipsum");
        $post->setCreationDate(new \DateTime());
        $post->setContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer maximus aliquet enim, et tristique sem feugiat sed. Praesent non commodo magna, in congue sem. Suspendisse elit nisi, mollis eget neque nec, luctus lobortis justo. Mauris ac turpis velit. Quisque varius risus ut ex placerat, venenatis dignissim metus ultricies. Nullam sollicitudin feugiat sapien ut semper. Nullam tincidunt at risus vel accumsan. Nulla facilisi.");
        $manager->persist($post);

        $post = new Post();
        $post->setType("text");
        $post->setUser($user1);
        $post->setTitle("Lorem ipsum 2");
        $post->setCreationDate(new \DateTime());
        $post->setContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer maximus aliquet enim, et tristique sem feugiat sed. Praesent non commodo magna, in congue sem. Suspendisse elit nisi, mollis eget neque nec, luctus lobortis justo. Mauris ac turpis velit. Quisque varius risus ut ex placerat, venenatis dignissim metus ultricies. Nullam sollicitudin feugiat sapien ut semper. Nullam tincidunt at risus vel accumsan. Nulla facilisi.");
        $manager->persist($post);

        $post = new Post();
        $post->setType("link");
        $post->setUser($admin);
        $post->setTitle("Twitter");
        $post->setCreationDate(new \DateTime());
        $post->setLink("https://twitter.com/");
        $post->setCaption("Réseau social twitter");
        $manager->persist($post);

        $post = new Post();
        $post->setType("link");
        $post->setUser($user1);
        $post->setTitle("Facebook");
        $post->setCreationDate(new \DateTime());
        $post->setLink("https://www.facebook.com/");
        $post->setCaption("Réseau social facebook");
        $manager->persist($post);

        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }
}