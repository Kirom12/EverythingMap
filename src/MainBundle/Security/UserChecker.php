<?php

namespace MainBundle\Security;

//use AppBundle\Exception\AccountDeletedException;
use MainBundle\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// Checkers : http://symfony.com/doc/current/security/user_checkers.html

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user is deleted, show a generic Account Not Found message.
//        if ($user->isDeleted()) {
//            throw new AccountDeletedException('...');
//        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (!$user->getValid()) {
            throw new AuthenticationException('Valid your account before log in !');
        }

        // user account is expired, the user may be notified
//        if ($user->isExpired()) {
//            throw new AccountExpiredException('...');
//        }
    }
}