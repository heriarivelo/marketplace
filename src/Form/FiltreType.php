<?php

namespace App\Form;

use App\Classes\Filtre;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class FiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('string', TextType::class,[
                'required' => false,
                'attr' => [
                    'placeholder' => 'Votre recherche ...',
                     'class' => 'form-control border-0'
                ]
            ])
            // ->add('slug')
            // ->add('illustration')
            // ->add('subtitle')
            // ->add('description')
            // ->add('prix')
            // ->add('categories', EntityType::class, [
            //     'label' => false,
            //     'required' => false,
            //     'class' => Category::class,
            //     'multiple' => true, 
            //     'expanded' => true
            // ])
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])

        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Filtre::class,
            'method' => 'GET',
            'crsf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Product::class,
    //     ]);
    // }
}
