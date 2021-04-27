<?php

namespace App\Controller;

use App\Repository\OrderDraftRepository;
use App\Repository\UserRepository;
use App\Services\SessionServices;
use App\Services\ShopServices;
use App\Services\UserAdminServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [ 'last_username' => $lastUsername, 'error' => $error ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * Display & process form to request a password reset.
     *
     * @Route("delete_user_admin", name="delete_user_admin")
     * @param Request                $request
     * @param UserAdminServices      $userAdminServices
     * @param UserRepository         $userRepository
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function deleteUserAdmin(Request $request, UserAdminServices $userAdminServices, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $email = $request->attributes->get('email');
        $userAdminServices->deleteUser($email, $userRepository, $entityManager);

        return $this->redirectToRoute('admin');

    }


    /**
     * Display & process form to request a password reset.
     *
     * @Route("edit_user_admin", name="edit_user_admin")
     * @param Request                      $request
     * @param UserAdminServices            $userAdminServices
     * @param UserRepository               $userRepository
     * @param EntityManagerInterface       $entityManager
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return Response
     */
    public function editUserAdmin(Request $request, UserAdminServices $userAdminServices, UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder): Response
    {
        $email = $request->attributes->get('email');
        $userAdminServices->editUserAdmin($email, $userRepository, $entityManager, $encoder);
        return $this->redirectToRoute('admin');

    }

}
