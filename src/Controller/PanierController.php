<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Service\Panier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierController extends AbstractController
{

    /**
     * @Route("/panier", name="panier")
     */
    public function panier(SessionInterface $session, Panier $panierObjet)
    {
        $monPanier = $session->get('panier');

        dump($monPanier);
        $montantTotal=$panierObjet->montantTotal();


        return $this->render("panier/panier.html.twig",[
            "monPanier"=>$monPanier,
            "montantTotal"=>$montantTotal
        ]);
    }


    /**
     * @Route("/panier/ajouter", name="panier_ajouter")
     */
    public function panier_ajouter(Request $request, ProduitRepository $repoProduit, Panier $panier)
    {
        //dd($request->request);

        /*
            lorsqu'un utilisateur ajoute un produit dans son panier
            les 2 données du formulaires obligatories sont :
            "quoi ?" => l'id du produit
            "combien ?" => la quantité saisie par l'utilisateur

            on le réception dans la propriété request ($_POST) de l'objet request issu de la class Request 
        */

        $quantite = $request->request->get('quantite');
        $id_produit = $request->request->get('id');


        //dump($quantite);
        //dd($id_produit);
        $produit = $repoProduit->find($id_produit);
        //dd($produit);
        
        // injecter dans la fonction ajouter 
        $panier->add($produit->getTitre(), $id_produit, $quantite, $produit->getPrix());

        return $this->redirectToRoute('panier');

        // redirection sur la fiche produit de $idproduit
    }

/**
 * @Route("/panier/vier", name="panier_vider")
 */
public function panier_vider(Panier $panier)
{
    $panier->vider();
    return $this->redirectToRoute("panier");
}

/**
 * @Route("panier/retirer/{id}", name="panier_retirer")
 */
public function panier_retirer($id, Panier $panier)
    {
        $panier->remove($id);
        return $this->redirectToRoute("panier");
    }

}


