<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManager;
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

class AdminProduitController extends AbstractController
{
    /*

        CRUD : 
        Create(INSRT INTO) 
        Read (SELECT)
        Update 
        Delete

        /gestion_produit/afficher      name="produit_afficher"
        /gestion_produit/ajouter       name="produit_ajouter"
        /gestion_produit/modifier/id      name="produit_modifier"
        /gestion_produit/supprimer/id     name="produit_supprimer"

        _____________________________________________________________________________________________________

        MVC :
        Model :
            - Entity (=table)
            - Repository (= Requête SELECT)
            - EntityManagerInterface (= Requêtes INSERT INTO - UPDATE / DELETE )
                                                  persist($objet)     /  remove($objet)
                                                                  flush()




    */



    /**
     * La fonction produit_afficher() permet d'afficher la table produit en bdd sous forme de tableau (BACK-OFFICE)
     * Sur chaque ligne, on y trouvera les routes pour modifier et supprimer
     * également on trouvera la route pour ajouter un produit 
     * 
     * @Route("/gestion_produit/afficher", name="produit_afficher")
     */
    public function produit_afficher(ProduitRepository $repoProduit)
    {
        $produitsArray = $repoProduit->findAll();

        //dd($produitsArray);// c'est un tableau d'objets



        return $this->render('admin_produit/produit_afficher.html.twig', [
            "produits" => $produitsArray
        ]);
    }




    /**
     * La fonction produit_ajouter() permet d'ajouter un produit
     * 
     * 
     * @Route("/gestion_produit/ajouter", name="produit_ajouter")
     */
    public function produit_ajouter(Request $request, EntityManagerInterface $manager)
    {
        // Pour ajouter un produit on a besoin de créer un nouvel objet (instance) issu de la class Produit(Entity)

        $produit = new Produit;
        dump($produit);// on observe qu'il y a toutes les propriétés de la class Produit (id, titre, prix etc...) et qu'elles sont null


        /*
            Pour créer un formulaire, on utilise la méthode createForm() provenant de la class AbstractController
            2 arguments obligatoires :
            1er : class du formType : ProduitType::class
            2e : objet issu de la class (entity)

            3e (facultatif) : tableau
        */

            $form = $this->createForm(ProduitType::class, $produit, array ("ajouter"=>true));
            // $form est un objet (qui contient ses méthodes)



            $form->handleRequest($request); 
            /*
                HandleRequest() permet de gérer le traitement de la saisie du formulaire.
                Lorsque qu'on soumet le formulaire (bouton submit) $_POST est transmis à la même URL
                grâce à la request, on peut traiter le contenu de la requête 

                La class Request contient les propriétés concernant les superglobales
                request = $_POST
                query = $_GET
                files = $_FILES ....

                ex pour appeler le $_POST : $request->request 


            */

            // si le formulaire a été soumis (clic sur le bouton de type="submit")
            // et si le formulaire a été validé (respect des conditions/contraintes)
            if($form->isSubmitted() && $form->isValid())
            {




                $imageFile = $form->get('image')->getData();
                /*
                    Dans l'objet form, se trouve une méthode get dans laquelle on mentionne le nom d'un input (cf : ProduitType)
                    on demande les données de l'input ->getData()

                    $imageFile est soit un objet soit null
                */

                //dd($imageFile);

                if($imageFile) // si $imageFile n'est pas vide/null ==> une image a été upload
                {

                    // 1e étape : renommer l'image 
                    // rendre le nom de l'image unique pour éviter d'avoir des images du même nom (une écrasera l'autre)

                    $nomImage = date("YmdHis") . "-" . uniqid() . "-" . $imageFile->getClientOriginalName();
                    /*
                        récupérer le moment présent : année mois jour heure minute seconde
                        uniqid() une fonction qui génère un identifiant unique 13 caractères composé de chiffres et lettres
                    */

                    //dd($nomImage);


                    // 2e : Envoyer l'image dans le dossier public / images / imagesUpload

                    $imageFile->move(
                        $this->getParameter("image_produit"),
                        $nomImage
                    );
                    /*
                        dans l'objet  $imageFile, se trouve une méthode move() qui permet de déplacer un fichier dans le projet
                        2 arguments :
                        1e : emplacement
                        2e : nom de l'image

                        getParameter() une méthode d'AbstractController qui fait référence à 'parameters' du fichier config/services.yaml ligne 6
                        il prend comme argument le nom du paramètre

                        dans services.yaml
                        image_produit: '%kernel.project_dir%/public/images/imagesUpload'
                        %kernel.project_dir% => le projet sf (ex : bijouterie)

                        on peut appeler un dossier qui n'existe 
                    */

                    // 3e : Insérer le nom de l'image dans l'objet $produit

                    $produit->setImage($nomImage);




                    


                }
                // s'il n'y a pas d'image upload on ne rentre pas dans la condition et donc on continue la suite du code 








                $produit->setDateAt(new \DateTimeImmutable('now'));
                /*
                    Lorsqu'on importe une class provenant du projet symfony on doit définir d'où elle provient par le 'use'
                    DateTimeImmutable n'est pas une class créée par Symfony, on la trouve sur PHP
                    il faut utiliser devant la class l'antislash
                */



                $manager->persist($produit); // on persiste ce qu'on souhaite envoyer en BDD : l'objet $produit
                // on ne définit pas dans quelle table, car on envoit un objet issu d'une class (= Entity)
                // persist() => INSERT INTO / UPDATE
                $manager->flush(); // envoie en BDD
                dump($produit);

                // dd($produit);

                //On observe qu'après envoie de l'objet en BDD, il récupère son ID provenant de la table
                

                /*
                    Notification: le produit N°ID a bien été ajouté
                    Méthode addFlash() provenant de la class AbstractController
                    2 arguments obligatoires :
                    1e : le nom du flash (à choisir)
                    2e : le message

                    -----------------------
                    Controller => Création 
                    Twig => Réception (affichage)

                */


                $this->addFlash("success", "Le produit N°" . $produit->getId() . " a bien été ajouté");

               
                /*
                    Redirection (dernière étape après envoie)
                    Méthode redirectToRoute() provenant de la class AbstractController
                    2 arguments :
                    1er (obligatoire) : name de la route
                    2e (facultatif) : tableau des paramètres []
                    
                */

                return $this->redirectToRoute("produit_afficher");
            }


        return $this->render("admin_produit/produit_ajouter.html.twig",[
            "formProduit" => $form->createView()
        ]);
       
    }




    /**

     * @Route("/gestion_produit/modifier/{id<\d+>}", name="produit_modifier")
     */
    public function produit_modifier(Produit $produit, Request $request, EntityManagerInterface $manager) // objet de la class Produit
    {
        /* $produitsArray = $repoProduit->findAll(); */

        //dd($produitsArray);// c'est un tableau d'objets


/*         dd ($produitObject);
 */
        $form = $this->createForm(ProduitType::class, $produit, array("modifier"=>true));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            /*
                pour ajouter un produit :
                2 possibilités
                avec image
                sans image
                _________________________

                pour modifier un produit :
                5 possibilités
                
                SANS IMAGE => SANS IMAGE (OK)
                SANS IMAGE => AVEC IMAGE (UPLOAD) (ok)

                AVEC IMAGE => AVEC LA MEME IMAGE (OK)
                AVEC IMAGE => CHANGER IMAGE (UPLOAD + SUPP L'ANCIENNE IMAGE DU DOSSIER IMAGEUPLOAD)
                AVEC IMAGE => SANS IMAGE    (SUPP L'IMAGE DU DOSSIER IMAGEUPLOAD)

            */
        $imageFile=$form->get('imageFile')->getData();

        if($imageFile){

            $nomImage = date("YmdHis") . "-" . uniqid() . "-" . $imageFile->getClientOriginalName();

            $imageFile->move(
                $this->getParameter("image_produit"), // dans services yaml, on upload dans image upload
                $nomImage
            );

            //dump($produit->getImage());

            if($produit->getImage())
            {

                /*
                    fonction php unlink() permet de supprimer un fichier
                    1 argument : emplacement suivi du nom du fichier
                */
                unlink($this->getParameter("image_produit") . '/' . $produit->getImage());
            }
            $produit->setImage($nomImage); // on redéfinit la propriété image qui est le nom de l'image

        }

        $manager->persist($produit); //avec persist on peut ajouter ou modifier un produit. Si l'id est null, il va créer le produit si l'id
        // existe, il va l'update.
        $manager->flush(); 

        $this->addFlash("success", "Le produit N°" . $produit->getId() . " a bien été modifié");

        return $this->redirectToRoute("produit_afficher");
 
        }

        return $this->render('admin_produit/produit_modifier.html.twig', [
            "produit" => $produit, /* ce 2eme argument est utile si on veut afficher des données de la variable dans le twig */
        "formProduit"=>$form->createView()]);
    }


/*

    Retirer l'image d'un produit
    par une route :
    --------------

    créer un lien (icône suppression) lorsqu'il y a une image sur la route produit_modifier 
     
    le lien redirige sur une nouvelle route
    /gestion_produit/image/supprimer/{id}     name="image_produit_supprimer"

    et dans la fonction :

    supprimer le fichier image dans le dossier imageUpload

    repasser la propriété image de l'objet produit à null

    manager

    addflash

    redirection sur la route produit_modifier  il y a le 2e argument tableau des paramètres

    */


/**
 * @Route("/gestion_produit/image/supprimer/{id}", name="image_produit_supprimer") 
 * 
 * 
 */


 public function image_produit_supprimer(Produit $produit, EntityManagerInterface $manager )
 {
    unlink($this->getParameter("image_produit") . '/' . $produit->getImage()); 

    $produit->setImage(null);
    dump($produit); 
    $manager->persist ($produit);
    $manager->flush ();

    $this->addFlash("success", "L'image" . $produit->getId() . " a bien été modifiée");



    return $this->redirectToRoute("produit_modifier", ["id"=>$produit->getId()]);
 }


 
/**
 * @Route("/gestion_produit/supprimer/{id}", name="produit_supprimer") 
 * 
 * 
 */
 public function produit_supprimer(Produit $produit, EntityManagerInterface $manager ){

    if($produit->getImage()){

        unlink($this->getParameter("image_produit") . '/' . $produit->getImage()); //si mon produit contient une image alors je la supprr
        
    }
        $idProduit=$produit->getId();
        $manager->remove($produit);
        $manager->flush ();

        $this->addFlash("success","le produit $idProduit bien été modifié"); 
    

    return $this->redirectToRoute("produit_afficher");


 }







}
