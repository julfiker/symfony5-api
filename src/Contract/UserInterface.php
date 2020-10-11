<?php


namespace App\Contract;


use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

interface UserInterface
{
    public function createUser(Request $request);

    public function decrementUserUnity(User $user);
}