<?php

namespace App\Form;

use App\Entity\Formations;
use App\Entity\Ecoles;
use App\Entity\TypeFormation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class FormationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('dateDebut', DateType::class, [
                'label' => "Date de commencement: ",
                'widget' => 'single_text',
               
            ])
            ->add('dateFin', DateType::class, [
                'label' => "Date de fin : ",
                'widget' => 'single_text',
               
            ])
            ->add('ecole', EntityType::class, [
                'label' => "Ecole : ",
                'class' => Ecoles::class,
                'choice_label' => 'libelle',
            ])
            ->add('typeFormation', EntityType::class, [
                'label' => "Type de formation : ",
                'class' => TypeFormation::class,
                'choice_label' => 'libelle',
            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formations::class,
        ]);
    }
}
