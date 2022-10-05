<?php

namespace App\Form;

use App\Entity\Trajet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TrajetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('villeDepart', EntityType::class, [
            'label' => "Ville de départ :&nbsp;",
            'class' => Ville::class,
            'choice_label' => 'ville',
        ])
        ->add('villeArrive', EntityType::class, [
            'label' => "Ville d'arrivé : ",
            'class' => Ville::class,
            'choice_label' => 'ville',
        ])
      
        ->add('nbPlace', ChoiceType::class, [
            'label' => "Nombre de places disponibles : ",
            'choices'  => [
                '1' => 1,
                '2' => 2,
                '3' => 3,
                '4' => 4,
                '5' => 5,
            ]
            ])
        ->add('commentaire', TextType::class, [
            'label' => "Commentaire : ",
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
