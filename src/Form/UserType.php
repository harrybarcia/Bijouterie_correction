<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',TextType::class,["required"=>false])
            ->add('password',TextType::class,["required"=>false,"label"=>"mot de passe"])
            ->add('confirmPassword',TextType::class,["required"=>false,"label"=>"Confirmation du mot de passe"])
            ->add('nom',TextType::class,["required"=>false])
            ->add('prenom',TextType::class,["required"=>false,"label"=>"Prénom"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
