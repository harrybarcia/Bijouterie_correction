<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\Matiere;
use App\Entity\Produit;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if($options["ajouter"] == true)
        {

        
            $builder
                ->add('titre', TextType::class, [
                    "label" => "Titre du produit",
                    "required" => false,
                    "attr" => [
                        "placeholder" => "Saisir le titre du produit",
                        "class" => "bg-light",  
                    ],
                    // "constraints" => [

                    //     new NotBlank([
                    //         'message' => "Veuillez saisir un titreeeeeeeeeeee"
                    //     ]),
                    //     new Length([
                    //         'min' => 5,
                    //         'max' => 30,
                    //         'minMessage' => "5 caractères min",
                    //         "maxMessage" => "30 caractères max"
                    //     ])

                    // ]


                ])
                ->add('prix', MoneyType::class, [
                    "currency" => "EUR",
                    //"label" => "Prix du produit",
                    "required" => false,
                    "attr" => [
                        "placeholder" => "Saisir le prix du produit",
                        "class" => "bg-light",
                        
                        
                    ]
                ])
                ->add('image', FileType::class, [
                    "required" => false,
                    //"multiple" => true
                    "constraints" => [
                        new File([
                            'mimeTypes' => [
                                "image/png", 
                                "image/jpg",
                                "image/jpeg"
                            ],
                            'mimeTypesMessage' => "les extensions des images autorisées sont : PNG - JPG"
                        ])
                    ]
                ])

                                
                ->add('category', EntityType::class, [ // cet input a une relation avec une autre entity
                    "class" => Category::class,        // avec quelle entity
                    "choice_label" => "nom",          // quelle propriété (quel champ) afficher
                    "placeholder" => "Saisir une catégorie"
                ])
                
                ->add('marque', EntityType::class, [ // cet input a une relation avec une autre entity
                    "class" => Marque::class,        // avec quelle entity
                    "choice_label" => "nom",          // quelle propriété (quel champ) afficher
                    "placeholder" => "Saisir une marque",
                    "expanded"=>true,
                ])

                ->add('matieres', EntityType::class, [ // cet input a une relation avec une autre entity
                    "class" => Matiere::class,        // avec quelle entity
                    "choice_label" => "nom",          // quelle propriété (quel champ) afficher
                    "placeholder" => "Saisir une matiere",
                    "expanded"=>true,
                    "multiple" => true

                ])

                //->add('Ajouter', SubmitType::class)

                /*
                    Dans une méthode add()
                    1e argument (obligatoire) : nom de l'input
                    2e argument : type de l'input text, textarea, checkbox, radio, select, color, hidden, submit, number, password
                    3e argument : tableau de configuration
                */
            ;

        }
        elseif($options["modifier"] == true)
        {

        
            $builder
                ->add('titre', TextType::class, [
                    "label" => "Titre du produit",
                    "required" => false,
                    "attr" => [
                        "placeholder" => "Saisir le titre du produit",
                        "class" => "bg-warning",
                        
                        
                    ]
                ])
                ->add('prix', MoneyType::class, [
                    "currency" => "USD",
                    //"label" => "Prix du produit",
                    "required" => false,
                    "attr" => [
                        "placeholder" => "Saisir le prix du produit",
                        "class" => "bg-warning",
                        
                        
                    ]
                ])
                ->add('imageFile', FileType::class, [
                    "required" => false,
                     "mapped" => false, // imageFile n'est pas une propriété de l'entity
                    //"multiple" => true
                    "label" => "image à charger"
                ])
                ->add('category', EntityType::class, [ // cet input a une relation avec une autre entity
                    "class" => Category::class,        // avec quelle entity
                    "choice_label" => "nom",          // quelle propriété (quel champ) afficher
                    "placeholder" => "Saisir une catégorie"
                ])

                ->add('marque', EntityType::class, [ // cet input a une relation avec une autre entity
                    "class" => Marque::class,        // avec quelle entity
                    "choice_label" => "nom",          // quelle propriété (quel champ) afficher
                    "placeholder" => "Saisir une marque",
                    "expanded"=>true,
                ])

                ->add('matieres', EntityType::class, [ // cet input a une relation avec une autre entity
                    "class" => Matiere::class,        // avec quelle entity
                    "choice_label" => "nom",          // quelle propriété (quel champ) afficher
                    "placeholder" => "Saisir une matiere",
                    "expanded"=>true, //radio
                    "multiple" => true //plusieurs choix
                ])
            ;

            
                //->add('Ajouter', SubmitType::class)

                /*
                    Dans une méthode add()
                    1e argument (obligatoire) : nom de l'input
                    2e argument : type de l'input text, textarea, checkbox, radio, select, color, hidden, submit, number, password
                    3e argument : tableau de configuration
                */


        }


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class, 
            /* produit type est relié à la classe produit */
            "ajouter" => false,
            "modifier" => false
        ]);
    }
}
