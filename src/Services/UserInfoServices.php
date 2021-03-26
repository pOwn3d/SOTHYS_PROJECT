<?php


namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserInfoServices extends AbstractController
{


    public function getSocietyUser($userId, $societyRepository)
    {
        return $societyRepository->getSociety($userId);
    }
}
