<?php

namespace App\Manager;

use App\Contract\UserInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * A Service Manager to handle a user all operation
 *
 * Class UserManager
 * @package App\Manager
 */
class UserManager implements UserInterface
{
    /** @var UserPasswordEncoderInterface  */
    protected $encoder;
    /** @var EntityManagerInterface  */
    protected $em;

    /**
     * UserManager constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $em
     */
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $this->encoder = $encoder;
        $this->em = $em;
    }

    /***
     * Create an user as request by register
     *
     * @param Request $request
     * @return User
     * @throws \Exception
     */
    public function createUser(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $email = $request->get('email');
        if (empty($username) || empty($password) || empty($email)){
            throw new \Exception();
        }
        $user = new User($username);
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setUnity(5);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    /**
     * Decrement user unity once zip request
     *
     * @param User $user
     * @throws \Exception
     */
    public function decrementUserUnity(User $user) {
       $unity = (int)$user->getUnity();
       if ($unity > 0) {
           $user->setUnity($unity-1);
           $this->em->persist($user);
           $this->em->flush();
           return $user;
       }

       throw new \Exception();
    }
}