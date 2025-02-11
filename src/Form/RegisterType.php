<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'constraints' => new Length(['min' => 2, 'max' => 30]),
            ])
            ->add('lastname', TextType::class, [
                'constraints' => new Length(['min' => 2, 'max' => 30]),
            ])
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type' =>  PasswordType::class,
                'invalid_message' => 'Le mot de passe et la onfirmation doivent etre identiques',
                'required' => true,

            ])
            // ->add('passwordconfirm', PasswordType::class, [
            //     'mapped' => false,
            // ])
            ->add('save', SubmitType::class, ['label' => 'Register']) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
