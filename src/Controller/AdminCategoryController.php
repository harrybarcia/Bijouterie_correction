<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    /**
     * Cette route placée avant la classe permet d'intégrer a chaque route du controller un prefixe
     *
     * @Route("/admin")
     */

class AdminCategoryController extends AbstractController
{
    /*
        /gestion_category/afficher           name="category_afficher"       => category_afficher.html.twig
        /gestion_category/ajouter            name="category_ajouter"        => category_ajouter.html.twig
        /gestion_category/modifier/{id}      name="category_modifier"        => category_modifier.html.twig
        /gestion_category/supprimer/{id}     name="category_supprimer"
        
    */


    /**
     * @Route("/gestion_category/afficher", name="category_afficher")
     */
    public function category_afficher(CategoryRepository $repoCategory)
    {

        $categoryArray = $repoCategory->findAll();
        //dd($categoryArray);
        return $this->render("admin_category/category_afficher.html.twig", [
            "categories" => $categoryArray
        ]);
    }



    /**
     * @Route("/gestion_category/ajouter", name="category_ajouter")
     * @Route("/gestion_category/modifier/{id}", name="category_modifier")
     */
    public function category_ajouter_modifier(Category $category = null, Request $request, EntityManagerInterface $manager)
    {
        /*

            Le code pour ajouter et modifier est identique
            à part l'objet
            Quand on ajoute une catégorie on créé un nouvel objet 
            Et si on modifie une catégorie, autrement dit cette catégorie existe et se trouve dans la base de données

            On a 2 routes pour la même fonction
            on doit donc différentier l'objet soit on injecte par la modifier le paramètre id dans l'objet en dépendance
            soit on doit créer un nouvel objet (pour l'ajout)


        */
        if(!$category)
        {
            $category = new Category;
        }

        //dd($category);
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $modif = $category->getId() !== null;

            $manager->persist($category);
            $manager->flush();

            $this->addFlash("success", ($modif) ? "La catégorie N°" . $category->getId() . " a bien été modifiée" : "La catégorie N°" . $category->getId() . " a bien été ajoutée" );
            
            return $this->redirectToRoute('category_afficher');
        }

        return $this->render("admin_category/category_ajouter_modifier.html.twig", [
            "formCategory" => $form->createView(),
            "category" => $category,
            "modification" => $category->getId() !== null
        ]);
    }



    #[Route("/gestion_category/supprimer/{id}", name:"category_supprimer")]

    public function category_supprimer(Category $category, EntityManagerInterface $manager){
    
    
        $manager->remove($category);
        $manager->flush();
    
        $this->addFlash("success","la categorie a bien été supprimée"); 
    
    
        return $this->redirectToRoute("category_afficher");
    }


























































    // /**
    //  * @Route("/gestion_category/ajouter", name="category_ajouter")
    //  */
    // public function category_ajouter(Request $request, EntityManagerInterface $manager)
    // {

    //     $category = new Category;
    //     //dd($category);

    //     $form = $this->createForm(CategoryType::class, $category);

    //     $form->handleRequest($request);

    //     if($form->isSubmitted() && $form->isValid())
    //     {
    //         $manager->persist($category);
    //         $manager->flush();

    //         $this->addFlash("success", "La catégorie N°" . $category->getId() . " a bien été ajoutée");
            
    //         return $this->redirectToRoute("category_afficher");

    //     }

    //     return $this->render("admin_category/category_ajouter.html.twig", [
    //         "formCategory" => $form->createView()
    //     ]);
    // }


    // /**
    //  * @Route("/gestion_category/modifier/{id}", name="category_modifier")
    //  */
    // public function category_modifier(Category $category, Request $request, EntityManagerInterface $manager)
    // {
    //     //dd($category);
    //     $form = $this->createForm(CategoryType::class, $category);

    //     $form->handleRequest($request);

    //     if($form->isSubmitted() && $form->isValid())
    //     {
    //         $manager->persist($category);
    //         $manager->flush();

    //         $this->addFlash("success", "La catégorie N°" . $category->getId() . " a bien été modifiée");
            
    //         return $this->redirectToRoute('category_afficher');
    //     }

    //     return $this->render("admin_category/category_modifier.html.twig", [
    //         "formCategory" => $form->createView(),
    //         "category" => $category
    //     ]);
    // }
















}



