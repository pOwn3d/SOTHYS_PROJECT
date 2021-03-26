<?php

namespace App\Controller\Admin;

use App\Entity\Society;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SocietyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Society::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
