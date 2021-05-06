<?php

namespace App\Form;

use App\Entity\OrderDraft;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderDraftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price')
            ->add('state')
            ->add('quantity')
            ->add('quantityBundling')
            ->add('priceOrder')
            ->add('idSociety')
//            ->add('idItem')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderDraft::class,
        ]);
    }
}
