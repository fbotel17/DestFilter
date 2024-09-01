<?php

namespace App\Form;

use App\Entity\Trajet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TrajetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('depart')
            ->add('arrivee')
            ->add('temps')
            ->add('observations', TextType::class, [
                'required' => false, // Rendre le champ facultatif
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trajet::class,
        ]);
    }
}
