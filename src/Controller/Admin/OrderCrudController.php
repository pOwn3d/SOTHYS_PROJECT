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
            IntegerField::new('idOrder', 'id.order'),
            IntegerField::new('idOrderX3', 'id.X3'),
            DateTimeField::new('dateOrder', 'order.date'),
            DateTimeField::new('dateDelivery', 'date.shipping'),
            IntegerField::new('idStatut', 'order.status'),
            IntegerField::new('idDownStatut', 'order.substatus'),
            TextField::new('reference', 'Reference'),
            DateTimeField::new('dateLastDelivery', 'date.latest.shipped'),

        ];
    }
}
