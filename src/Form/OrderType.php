<?php

namespace App\Form;

use App\Entity\CustomerIncoterm;
use App\Entity\Order;
use App\Entity\Society;
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
            ->add('reference')

            ->add("incoterm", EntityType::class, [
                'class'         => CustomerIncoterm::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->innerJoin(
                            CustomerIncoterm::class,    // Entity
                            'p',               // Alias
                            Join::WITH,        // Join type
                            'p.reference = u.id' // Join columns
                        )
                        ->andWhere('p.societyCustomerIncoterm = p.id');
                },
                'choice_label'  => 'reference',
            ])

            ->add("society", EntityType::class, [
                'class'         => Society::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->innerJoin(
                            Society::class,    // Entity
                            's',               // Alias
                            Join::WITH,        // Join type
                             // Join columns
                        );

                },
                'choice_label'  => 'name',
            ])



//            ->add('dateLastDelivery')
//            ->add('priceOrder')
//            ->add('SocietyID')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
