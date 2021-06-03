<?php

namespace App\Controller\Admin;

use App\Entity\FreeRules;
use App\Entity\Society;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FreeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FreeRules::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('labelFr', 'Label FR'),
            TextField::new('labelEn', 'Label EN'),
            AssociationField::new('idItemPurchased', 'ID Produit acheté'),
            AssociationField::new('idItemFree', 'ID Produit offert'),
            IntegerField::new('qtyPurchased', 'Quantité acheté'),
            IntegerField::new('qtyFree', 'Quantité gratuite'),
            IntegerField::new('amountPurchasedMin', 'Prix minimum'),
            IntegerField::new('amountPurchasedMax', 'Prix Maximum'),
            IntegerField::new('amountFree', 'Pourcentage gratuité'),
        ];
    }

}
