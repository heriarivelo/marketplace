<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('adresses', EntityType::class, [
                'class' => Address::class,
                // 'choice_label' => 'name',
                'required' => true,
                'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true
            ])
            ->add('carriers', EntityType::class, [
                'class' => Carrier::class,
                // 'choice_label' => 'name',
                'required' => true,
                // 'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true
            ])
            ->add('save', SubmitType::class, ['label' => 'Continue to checkout'])
            // ->add('field_name')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           'user' => array()
        ]);
    }
}
