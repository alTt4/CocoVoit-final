<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Ville;
use App\Entity\Formations;
use App\Entity\TyepUser;
use App\Entity\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label'=> "Email : ",
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => False,
                'label'=> "Acceptez vous nos termes ? ",
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label'=>"Mot de passe :   ",
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => "Nom : ",
            ])
            ->add('prenom', TextType::class, [
                'label' => "Prénom : ",
            ])
            ->add('permis', CheckboxType::class, [
                'label' => "Avez-vous le permis de conduire ? ",
                'required' => false,
            ])
            ->add('telephone', TextType::class, [
                'label' => "téléphone : " ,
            ])
            ->add('dateNaissance', DateType::class, [
                'label' => "Date de naissance : ",
                'widget' => 'single_text',
               
            ])
         
            ->add('formation', EntityType::class, [
                'label' => "formation : ",
                'class' => Formations::class,
                'choice_label' => 'libelle',
            ])
            -> add('image', FileType::class, [
                'label' => false,

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

              ])
            ->add('status', EntityType::class, [
                'label' => "Statut : ",
                'class' => Status::class,
                'choice_label' => 'libelle',
            ]);
       
     }  
        


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            
        ]);
    }
}
