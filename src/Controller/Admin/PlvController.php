<?php

namespace App\Controller\Admin;

use App\Entity\Plv;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class PlvController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Plv::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('labelFr', 'Nom FR'),
            TextField::new('labelEn', 'Nom EN'),
            ];
    }


}
