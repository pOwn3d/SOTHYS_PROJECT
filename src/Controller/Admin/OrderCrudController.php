<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            IntegerField::new('idOrder', 'ID Commande'),
            IntegerField::new('idOrderX3', 'ID X3'),
            DateTimeField::new('dateOrder', 'Date de commande'),
            DateTimeField::new('dateDelivery', 'Date de livraison'),
            IntegerField::new('idStatut', 'Statut de la commande'),
            IntegerField::new('idDownStatut', 'idDownStatut'),
            TextField::new('reference', 'Référence'),
            DateTimeField::new('dateLastDelivery', 'Date de dernière livraison'),
           
        ];
    }
}
