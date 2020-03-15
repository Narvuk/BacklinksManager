<?php

namespace App\Form\System\Setup;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class DbdetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dbuser', TextType::class)
            ->add('dbuserpass', TextType::class, array(
                'required' => false,
            ))
            ->add('dbhost', TextType::class)
            ->add('dbport', TextType::class)
            ->add('dbname', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // resolver types
        ]);
    }
}
