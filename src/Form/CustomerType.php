<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('email')
            ->add('phone')
            ->add('society')
            ->add('image', FileType::class, [
                'mapped' => false 
            ])
            ->add('city')
            // ->add('fidelityPoint', IntegerType::class,[
            //     'attr' => [
            //         // 'value' => 0,
            //         'min' => 0,
            //         // 'max' => 10,
            //     ]
            // ])
            ->add('service')
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
