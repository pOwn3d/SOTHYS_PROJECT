<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Services\Cart\CartItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/{_locale}/mon-compte", name="app_account", requirements={
    * "_locale"="%app.locales%"
    * })
     * @param Request                      $request
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface       $em
     * @param CartItem                     $cartItem
     *
     * @return Response
     */
    public function index(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, CartItem $cartItem): Response
    {
        $dataUser = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->getUser()->getId());
        $userId   = $this->getDoctrine()->getRepository(User::class)->find($dataUser);
        $societyId = $this->getUser()->getSocietyID()->getId();

        $form = $this->createForm(UserType::class, $dataUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $userRepository->updatePassword($userId, $encoder, $password, $em);
            $this->addFlash('success', 'Modification prise en compte');
        }

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'form'            => $form->createView(),
            'user'            => $userId,
            'cartItem'        => $cartItem->getItemCart($societyId)
        ]);
    }
}
