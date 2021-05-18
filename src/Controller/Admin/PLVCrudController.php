<?php

namespace App\Controller\Admin;

use App\Entity\Plv;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PLVCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Plv::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('name'),
            AssociationField::new('promotion'),
        ];
    }

}
