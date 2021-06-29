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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderType extends AbstractType
{

    public function __construct(Security $security, TranslatorInterface $translator)
    {
        $this->security = $security;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $minOrderDate = new \DateTime();
        $minOrderDate->add(\DateInterval::createFromDateString('15 days'));

        $builder
            ->add('dateDelivery', DateType::class, [
                'widget' => 'single_text',
                'label' => 'order.delivery_date',
                'attr' => [
                    'min' => $minOrderDate->format('Y-m-d'),
                ]
            ])
            ->add('reference', TextType::class, [
                'attr' => [
                    'required' => true,
                ]
            ])
            ->add("incoterm", EntityType::class, [
                'class'         => CustomerIncoterm::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('u')
                        ->innerJoin(
                            Incoterm::class,    // Entity
                            'p',               // Alias
                            Join::WITH,        // Join type
                            'p.id = u.reference' // Join columns
                        )
                        ->andWhere('u.societyCustomerIncoterm = :societyId')
                        ->setParameter('societyId', $options['societyId']);
                },
                'choice_label' => function (CustomerIncoterm $customerIncoterm) {
                    return $customerIncoterm->getModeTransport()->getName($this->translator->getLocale()) . ' - ' . $customerIncoterm->getReference() . ' : ' . $customerIncoterm->getCity();
                },
            ])
            ->add('address', EntityType::class, [
                'class' => Address::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('a')
                        ->innerJoin(
                            Society::class,
                            's',
                            Join::WITH,
                            's.id = a.society'
                        )
                        ->andWhere('s.id = :societyId')
                        ->andWhere('a.label LIKE \'L%\'')
                        ->setParameter('societyId', $options['societyId']);
                },
                'choice_label' => function(Address $address) {
                    return $address->getLabel() . ' - ' . $address->getAddress1() . ' ' . $address->getPostalCode() . ' ' . $address->getCity() . ' ' . $address->getCountry();
                }
            ])
            ->add('paymentMethod', EntityType::class, [
                'class' => PaymentMethod::class,
                'query_builder' => function(EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                    ->innerJoin(
                        Society::class,
                        's',
                        Join::WITH,
                        's.paymentMethod = p.id'
                    )
                    ->andWhere('s.id = :societyId')
                    ->setParameter('societyId', $options['societyId']);
                },
                'choice_label' => function(PaymentMethod $paymentMethod) {
                    return $paymentMethod->getLabel($this->translator->getLocale());
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'allow_extra_fields' => true,
            'societyId' => null,
        ]);
    }
}
