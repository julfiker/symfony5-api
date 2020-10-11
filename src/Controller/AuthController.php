<?php

namespace App\Controller;


use App\Contract\UserInterface;
use App\Entity\User;
use App\Manager\UserManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AuthController extends ApiController
{

    /**
     * @param Request $request
     * @param UserInterface $userManager
     * @return JsonResponse
     */
    public function register(Request $request, UserInterface $userManager)
    {
        try {
            $request = $this->transformJsonBody($request);
            $user = $userManager->createUser($request);
            return $this->respondWithSuccess(sprintf('User %s successfully created', $user->getUsername()));
        }
        catch (\Exception $e) {

            return $this->respondValidationError($e->getMessage()."Invalid Username or Password or Email");
        }
    }

    /**
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
    public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager)
    {
        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }
}