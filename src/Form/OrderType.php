<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\CustomerIncoterm;
use App\Entity\Incoterm;
use App\Entity\Order;
use App\Entity\PaymentMethod;
use App\Entity\Society;
use App\Entity\TransportMode;
use App\Repository\CustomerIncotermRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderType extends AbstractType
{

    private $security;

    public function __construct(Security $security, TranslatorInterface $translator)
    {
        $this->security = $security;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('idOrder')
//            ->add('idOrderX3')
//            ->add('dateOrder')
            ->add('dateDelivery', DateType::class, [
                'widget' => 'single_text',
                'label' => 'order.delivery_date',
            ])
//            ->add('idStatut')
//            ->add('idDownStatut')
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
                        ->andWhere('u.societyCustomerIncoterm = :societyId')
                        ->setParameter('societyId', $this->security->getUser()->getSocietyID()->getId());
                },
                'choice_label' => function (CustomerIncoterm $customerIncoterm) {
                    return $customerIncoterm->getModeTransport()->getName($this->translator->getLocale()) . ' - ' . $customerIncoterm->getReference() . ' : ' . $customerIncoterm->getCity();
                },
            ])
            ->add('address', EntityType::class, [
                'class' => Address::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->innerJoin(
                            Society::class,
                            's',
                            Join::WITH,
                            's.id = a.society'
                        )
                        ->andWhere('s.id = :societyId')
                        ->setParameter('societyId', $this->security->getUser()->getSocietyID());
                },
                'choice_label' => function(Address $address) {
                    return $address->getLabel() . ' - ' . $address->getAddress1() . ' ' . $address->getPostalCode() . ' ' . $address->getCity() . ' ' . $address->getCountry();
                }
            ])
            ->add('paymentMethod', EntityType::class, [
                'class' => Society::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                    ->innerJoin(
                        PaymentMethod::class,
                        'p',
                        Join::WITH,
                        's.paymentMethod = p.id'
                    )
                    ->andWhere('s.id = :societyId')
                    ->setParameter('societyId', $this->security->getUser()->getSocietyID());
                },
                'choice_label' => function(Society $society) {
                    return $society->getPaymentMethod()->getLabel($this->translator->getLocale());
                },
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
