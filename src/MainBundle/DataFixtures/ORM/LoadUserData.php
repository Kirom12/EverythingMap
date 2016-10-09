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
        $user->setLastName('Admin');
        $user->setFirstName('Admin');
        $user->setMail('admin@admin.com');
        $user->setMailCheck(uniqid().uniqid());
        $user->setValid(true);
        $user->setSalt('kq22ZsPh7Lp7DXCQwElFz8Oagv3qXP6YQe/O03xj');
        $user->setPassword('8GJ6hC8MVm0OKrhp1XPsaxsH34Hw6g67YlAm5ytm9O1EqSOwif0q+CFOItCieYUwv84mCH0YQC0NCz99XNjRzQ==');

        $user->addRoles('ROLE_ADMIN');
        $user->setCreatedDate(new \DateTime());
        $manager->persist($user);

        $this->addReference('admin', $user);

        $user = new User();
        $user->setPseudo('user1');
        $user->setMail('user1@user.com');
        $user->setMailCheck(uniqid().uniqid());
        $user->setValid(true);
        $user->setSalt('UlqHKg7YsGkr1YpXOVhNDblntzuW3Ec7Pr6UJeaE');
        $user->setPassword('QZFmReOepqTFzSQJM8xjYCmKzf0JCo+01nSwwJNmejMS9RHCOqeYcgULyXuERXNLi5zTn7yrbwCKozCQ/YXtGQ==');

        $user->addRoles('ROLE_USER');
        $user->setCreatedDate(new \DateTime());
        $manager->persist($user);

        $this->addReference('user1', $user);

        $user = new User();
        $user->setPseudo('user2');
        $user->setMail('user2@user.com');
        $user->setMailCheck(uniqid().uniqid());
        $user->setValid(true);
        $user->setSalt('fHYtv5SMGbiuwM3JokpQ81a3A7feUktC/fRpJgd2');
        $user->setPassword('wJYQtBPflsXd4dzt+22wO+NQ9HHMoDrUoGPptt9PwZzm7Cw28mTPIZwg/bLzNkOjE9/N0dbxV5xr+a6er/DSvw==');

        $user->addRoles('ROLE_USER');
        $user->setCreatedDate(new \DateTime());
        $manager->persist($user);

        $this->addReference('user2', $user);

        $manager->flush();
    }

    public function getOrder() {
        return 0;
    }
}