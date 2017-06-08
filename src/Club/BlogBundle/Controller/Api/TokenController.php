<?php

namespace Club\BlogBundle\Controller\Api;

use Club\BlogBundle\Controller\BaseController;
use Club\UserBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;



class TokenController extends BaseController
{
    /**
     * Creates a new Token for user
     *
     * @ApiDoc(
     *     statusCodes={
     *          200="Returned when successful",
     *          400="Returned when the posted data is invalid"
     *     }
     * )
     * @Route("/api/tokens")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function newTokenAction(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['username' => $username]);

        if (!$user) {
            throw $this->createNotFoundException('No user');
        }

        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $password);

        if (!$isValid) {
            throw new BadCredentialsException();
        }

        $token = $this->get('lexik_jwt_authentication.jwt_encoder')
            ->encode(['username' => $user->getUsername()]);

        return new JsonResponse([
            'token' => $token
        ]);
    }


}
