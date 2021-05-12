<?php

namespace App\Form;

use App\Entity\CustomerIncoterm;
use App\Entity\Incoterm;
use App\Entity\Order;
use Cassandra\Custom;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('idOrder')
//            ->add('idOrderX3')
//            ->add('dateOrder')
//            ->add('dateDelivery')
//            ->add('idStatut')
//            ->add('idDownStatut')
            // SELECT * FROM `customer_incoterm` INNER JOIN incoterm WHERE `society_customer_incoterm_id` = 8 AND incoterm.id = customer_incoterm.reference_id
            ->add('reference')
            ->add("incoterm", EntityType::class, [
                'class'         => CustomerIncoterm::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->innerJoin(
                            Incoterm::class,    // Entity
                            'p',               // Alias
                            Join::WITH,        // Join type
                            'p.id = u.reference' // Join columns
                        )
                        ->andWhere('u.societyCustomerIncoterm =  9');
                },
                'choice_label' => function (CustomerIncoterm $customerIncoterm) {
                    return $customerIncoterm->getReference() . ' ' . $customerIncoterm->getModeTransport();

                    // or better, move this logic to Customer, and return:
                    // return $customer->getFullname();
                },
            ])


            // TEST inner join
//            ->add("incoterm", EntityType::class, [
//                'class'         => CustomerIncoterm::class,
//                'query_builder' => function (EntityRepository $er) {
//                    $x = $er->createQueryBuilder('u')
//                        ->innerJoin(
//                            Incoterm::class,    // Entity
//                            'p',               // Alias
//                            Join::WITH,        // Join type
//                            'p.id = u.reference' // Join columns
//                        )
//                        ->innerJoin(
//                            User::class,    // Entity
//                            'o',               // Alias
//                            Join::WITH ,       // Join type
//                              'o.societyID = u.societyCustomerIncoterm' // Join colu
//                        )
//                        ->andWhere('o.societyID = u.societyCustomerIncoterm')
//                        ->getQuery()->getResult();
//                    dd($x);
////
//                },
//                'choice_label'  => 'reference',
//            ])


//            ->add("society", EntityType::class, [
//                'class'         => Society::class,
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('u')
//                        ->innerJoin(
//                            Society::class,    // Entity
//                            's',               // Alias
//                            Join::WITH,        // Join type
//                             // Join columns
//                        );
//
//                },
//                'choice_label'  => 'name',
//            ])


//            ->add('dateLastDelivery')
//            ->add('priceOrder')
//            ->add('SocietyID')
            ->add('email');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
