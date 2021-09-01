<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Service\DateFr;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{

    /*
        sur ProduitController :
        FRONT OFFICE DE PRODUIT

        route : catalogue    
        name  : catalogue


        route : fiche_produit
        name : fiche_produit

    */



    /**
     * La fonction catalogue() permet d'afficher la table produit en bdd (FRONT-OFFICE)
     * sur chaque produit on y trouvera le bouton pour accéder à la route fiche_produit 
     * 
     * @Route("/catalogue", name="catalogue")
     */
    public function catalogue(ProduitRepository $repoProduit)
    {
        /*
            Lorsqu'on créé une entity, est généré en même son Repository
            Repository : Requête SELECT

            1e façon, Création de l'objet issu de la class ProduitRepository
            On utilise la méthode getDoctrine() provenant de la class AbstractController
            Dans celle-ci se trouve une méthode getRepository() qui aura comme argument le nom de la class de l'entity
            $repoProduit = $this->getDoctrine()->getRepository(Produit::class);
            --------------------------------------------------------------------

            2e façon,
            c'est d'appeler en argument de la fonction catalogue() la class suivi de son objet
            ====> UNE DEPENDANCE
            (class $objet)

        */

        

        $produitsObjectArray = $repoProduit->findAll(); // SELECT * FROM produit
        // $produit = $repoProduit->find(7); // SELECT * FROM produit WHERE id = 7
        // $produits = $repoProduit->findBy(["titre" => "bague en or", "prix" => 100]); // SELECT * FROM produit WHERE id = 7



        // $produitsObjectArray = $repoProduit->findTout();
        // $produitsObjectArray = $repoProduit->findPrix(999.99);
        //$produitsObjectArray = $repoProduit->findIdentifiant(6);
        //$produitsObjectArray = $repoProduit->findOrderPrix();

        //$categories = [4];
        //$produitsObjectArray = $repoProduit->findCategorie($categories);
        //$produitsObjectArray = $repoProduit->findSearch("o");
        //$produitsObjectArray = $repoProduit->findBetween(100,300);
        //dd($produitsObjectArray);

        return $this->render('produit/catalogue.html.twig', [
            "produits" => $produitsObjectArray
        ]);
    }



    /**
     * La fonction fiche_produit() permet d'afficher les infos d'un produit EXISTANT
     * On trouve le lien de cette route dans les cards de la route Catalogue
     * 
     * la route fiche_produit a besoin d'un paramètre : l'id du produit 
     * côté twig : besoin du second argument de la fonction path() : le tableau des paramètres
     * ici le nom du paramètre s'appelle : id
     * pour le récupérer dans l'url on place ce nom entre accolade
     * 
     * la route fiche_produit n'existe pas,
     * la route fiche_produit/{id} existe
     * 
     * Dans la route :
     * {id?0} : s'il n'y a pas de paramètre id dans l'url on peut définir une valeur par défaut
     * 
     * id est un integer, on peut au minimum définir l'obligation que l'id soit un integer
     * {id<\d+>}
     * 
     * @Route("/fiche_produit/{id<\d+>}", name="fiche_produit")
     */
    public function fiche_produit(Produit $produitObject, DateFr $dateFr)
                // $id, ProduitRepository $repoProduit    
    {
        //dd($id);
        //$produitObject = $repoProduit->find($id);// SELECT * FROM produit WHERE id = $id

        //dump($produitObject);

        $date_objet = $produitObject->getDateAt();
        //dump($date_objet);

        $moisNum = $date_objet->format("m");
        //dump($moisNum);

        // -----------------------------------------------------------
        // $moisArrayFr = [
        //     "janvier",
        //     "février",
        //     "mars",
        //     "avril",
        //     "mai",
        //     "juin",
        //     "juillet",
        //     "août",
        //     "septembre",
        //     "octobre",
        //     "novembre",
        //     "décembre"
        // ];

        // dump($moisArrayFr);


        // foreach($moisArrayFr as $key => $value)
        // {
        //     if($key +1  == $moisNum)
        //     {
        //         $moisFr = $value;
        //     }
        // }

        // dump($moisFr);
        // ---------------------------------------------------------------


        


        // $moisFr = $dateFr->moisFr1($moisNum);



        $dateEntiere=$dateFr->moisFr2($date_objet);

        $produitObject=$dateFr->moisFr3($produitObject);

        // $dateEntiere = $date_objet->format("d") . " " . $moisFr . " " . $date_objet->format("Y");

        // dd($dateEntiere);

        // dd($produitObject);






















        // -id: 3
        // -titre: "bague en or"
        // -prix: 499.99
        // -dateAt: DateTimeImmutable @1629111427 {#687 ▶}
        // -image: "20210816125707-611a448305828-bague18.jpg"



        return $this->render("produit/fiche_produit.html.twig", [
            "produit" => $produitObject
        ]);
    }
    














}
