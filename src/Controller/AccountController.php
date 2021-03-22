<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     * @param Request                      $request
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface       $em
     *
     * @return Response
     */
    public function index(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em): Response
    {

        $dataUser = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->getUser()->getId());
        $userId = $this->getDoctrine()->getRepository(User::class)->find($dataUser);
        
        $form = $this->createForm(UserType::class, $dataUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $userRepository->updatePassword($userId, $encoder, $password, $em);
            $this->addFlash('success', 'Modification prise en compte');
        }
        
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'form' => $form->createView(),
        ]);
    }
}
