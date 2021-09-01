<?php

namespace App\Controller;

use App\Form\AccueilType;
use App\Repository\AccueilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */ 


class AdminAccueilController extends AbstractController
{
    #[Route('/gestion_accueil/afficher', name: 'accueil_afficher')]

    public function accueil_afficher(AccueilRepository $repoAccueil)
    {
        $accueil=$repoAccueil->find(1);
        dump($accueil);
        return $this->render('admin_accueil/accueil_afficher.html.twig',["accueil"=>$accueil]);
    }

    /**
     * @Route("/gestion_accueil/modifier", name="accueil_modifier")
     */
    public function accueil_modifier(AccueilRepository $repoAccueil,Request $request, EntityManagerInterface $manager)
    {
        $accueil=$repoAccueil->find(1);
        $form=$this->createForm(AccueilType::class, $accueil);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            

            $manager->persist($accueil);
            $manager->flush();

            $this->addFlash(
               'success',
               "le texte de la page d'accueil a bien été modifié"
            );
            
            return $this->redirectToRoute('accueil_afficher');
        }

        return $this->render('admin_accueil/accueil_modifier.html.twig', [
            "formAccueil"=>$form->createView()
        ]);
    }



}
