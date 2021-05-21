<?php

namespace App\Controller\Admin;

use App\Entity\PromotionItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class PromotionItemController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return PromotionItem::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('item', 'Produit'),
            TextField::new('price', 'Prix'),
        ];
    }


}
