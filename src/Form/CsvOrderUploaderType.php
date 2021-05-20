<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CsvOrderUploaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('update_csv_order', FileType::class,[
                'label' => 'upload your orders',
                'mapped' => false,
                'required' => true,
                'attr' => ['accept' => '.csv'],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                    ])
                ]
            ])
            ->add('upload', SubmitType::class);
        ;
    }
}
