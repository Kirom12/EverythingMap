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
        $user->setSalt('z/VB5F0YfYcQNVnZGxf0OGPuNVC/xG8jfLuQAlr+');
        $user->setPassword('dlIehF8bZP+uO2ywuGE9OveZdKUcwgiDxm9Pu7gd2GWNhg+Aeco53J4W5k/VLOevZzE/kgG3SB2EpY9MfG0+bw==');

        $user->addRoles('ROLE_USER');
        $user->setCreatedDate(new \DateTime());
        $manager->persist($user);

        $this->addReference('user1', $user);

        $manager->flush();
    }

    public function getOrder() {
        return 0;
    }
}