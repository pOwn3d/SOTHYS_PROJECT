<?php


namespace App\Services;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserAdminServices extends AbstractController
{

    public function deleteUser($email, UserRepository $userRepository, $entityManager)
    {
        $user = $userRepository->findOneBy([ 'email' => $email ]);

        $roles    = $user->getRoles();
        $rolesTab = array_map(function ($roles) {
            return $roles;
        }, $roles);

        if (in_array('ROLE_USER', $rolesTab, true) && in_array('ROLE_SUPER_ADMIN', $rolesTab, true))
            return $this->addFlash('danger', 'Vous ne pouvez pas supprimer un Administrateur');
        else
            $entityManager->remove($user);
        $entityManager->flush();

        return null;
    }

}
