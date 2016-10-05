<?php

namespace MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MainBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        //USERS
        $user = new User();
        $user->setPseudo('admin');
        $user->setPassword('admin');
        $user->setMail('admin@admin');
        $user->setSalt(uniqid());
        $user->addRoles('ROLE_ADMIN');
        $manager->persist($user);

        $this->addReference('admin', $user);

        $user = new User();
        $user->setPseudo('user1');
        $user->setPassword('user1');
        $user->setMail('user1@user');
        $user->setSalt(uniqid());
        $user->addRoles('ROLE_USER');
        $manager->persist($user);

        $this->addReference('user1', $user);

        $manager->flush();
    }

    public function getOrder() {
        return 0;
    }
}