<?php

namespace App\Controller\Admin;

use App\Entity\Promotion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class PromotionController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Promotion::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('dateStart', 'Date de début'),
            DateField::new('dateEnd', 'Date de fin'),
            TextField::new('nameFr', 'Nom FR'),
            TextField::new('nameEn', 'Nom EN'),
            AssociationField::new('promotionItem'),
            AssociationField::new('plv'),
            AssociationField::new('society'),

        ];
    }


}
