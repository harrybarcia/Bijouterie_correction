<?php 

namespace App\Service;

use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Panier 
{


    public $session;
    public $repoProduit;
    public $manager;

    public function __construct(SessionInterface $session, ProduitRepository $repoProduit, EntityManagerInterface $manager)
    {
        $this->session = $session;
        $this->repoProduit = $repoProduit;
        $this->manager = $manager;
    }


    public function creationPanier()
    {
        $panier = [
            'titre' => [],
            "id_produit" => [],
            "quantite" => [],
            "prix" => []
        ];

        return $panier;
    }



    public function add($titre, $id_produit, $quantite, $prix)
    {

        $panierSession = $this->session->get('panier');

        if(empty($panierSession))
        {
            $panierNew = $this->creationPanier();
            $this->session->set('panier', $panierNew);
            $panierSession = $this->session->get('panier');
        }

        //dd($panierSession);

        // dans les 2 cas, le tableau panier existe dans la session

        // on évite les doublons d'id_produit donc dans un premier temps on va chercher si l'id_produit (argument) existe déjà dans le tableau id_produit

        // fonction prédéfinie array_search
        // Retourne la position dans un tableau par rapport à la valeur recherchée
        // si la valeur n'existe pas, la fonction retourne false
        // 2 arguments :
        // 1e : valeur recherchée
        // 2e : tableau 

        $position_produit = array_search($id_produit, $panierSession["id_produit"]);
        //dd($position_produit);

        // ce produit existe déjà dans le panier 
        if(is_int($position_produit)) // contient la position par rapport à l'id
        {
            $panierSession["quantite"][$position_produit] += $quantite;
            $this->session->set('panier', $panierSession);
        }
        else // le produit n'existe pas dans le panier, on génère une nouvelle position dans les 4 tableaux
        {
            $panierSession["titre"][] = $titre;
            $panierSession["id_produit"][] = $id_produit;
            $panierSession["quantite"][] = $quantite;
            $panierSession["prix"][] = $prix;
            $this->session->set('panier', $panierSession);

        }





    } 



    public function vider()
    {
        // $panier = $this->creationPanier();
        // $this->session->set('panier', $panier);
        $this->session->remove("panier");
    }


    public function remove($id_produit_supprimer)
    {
        $panierSession = $this->session->get('panier');

        $position_produit = array_search($id_produit_supprimer, $panierSession['id_produit']);

        if(is_int($position_produit))
        {
            /*
                Fonction prédéfinie PHP
                array_splice
                => permet de supprimer une ou plusieurs lignes dans un tableau
                3 arguments :
                1e : le tableau
                2e : la position à partir de laquelle on veut supprimer
                3e : le nombre d'éléments à supprimer 
            */

            array_splice($panierSession['titre'], $position_produit, 1);
            array_splice($panierSession['id_produit'], $position_produit, 1);
            array_splice($panierSession['quantite'], $position_produit, 1);
            array_splice($panierSession['prix'], $position_produit, 1);

            $this->session->set('panier', $panierSession);
        }
    }

    public function montantTotal()
    {
        $panierSession = $this->session->get('panier');

        $total = 0;

        for($i = 0; $i < count($panierSession['id_produit']); $i++)
        {
            $total += $panierSession['prix'][$i] * $panierSession['quantite'][$i];
        }

        return round($total, 2);

    }










}