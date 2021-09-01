<?php

namespace App\Controller;

use App\Form\PasswordUpdateType;
use App\Form\UserType;
use App\Service\PasswordUpdate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/profil")
 */

class UserController extends AbstractController
{
    /**
     * @Route("", name="profil")
     */
    public function profil()
    {
        // La méthode getUser() permet de récupérer l'objet user provenant de la table User de l'utilisateur connecté
        
        $user = $this->getUser();
        //dd($user);

        return $this->render('user/profil.html.twig');
    }


    /**
     * @Route("/modification", name="profil_modification")
     */
    public function profil_modification(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        //dump($user);

        //$user->confirmPassword = $user->getPassword();
        //dd($user);

        $form = $this->createForm(UserType::class, $user, ["profil" => true]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Les données de votre profil ont bien été modifiées");
            return $this->redirectToRoute("profil");

        }


        return $this->render("user/profil_modification.html.twig", [
            "formUser" => $form->createView()
        ]);
    }

    /**
     * @Route("/mot_de_passe/modification", name="password_modification" )
     */
    public function password_modification(Request $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $manager)
    {

        $user = $this->getUser(); // objet de l'utilisateur connecté
        $passwordUpdate = new PasswordUpdate;
        // dd($passwordUpdate); montre 3 propriétés vides;   -oldPassword: null
        //   -newPassword: null
        //   -confirmPassword: null

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            

            //dd($passwordUpdate);
            /*
                1e étape : comparer oldPassword avec le mot de passe en BDD
            */

            // si l'ancien mot de passe (du formulaire) n'est pas égal à celui encodé en bdd
            // on rentre dans la condition qui créée un message d'erreur  
            if(!$encoder->isPasswordValid($user, $passwordUpdate->getOldPassword())) // boolean
            {
                $form->get('oldPassword')->addError(new FormError("L'ancien mot de passe est incorrect"));
            }
            else // oldPassword == $user->getPassword() ==> traitement des données, encoder newPassword puis l'injecter dans l'objet $user avant de persist et flush
            {
                $hash = $encoder->hashPassword($user, $passwordUpdate->getNewPassword());
                //dd($hash);

                $user->setPassword($hash);
                //dd($user->getPassword());
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                   'success',
                   'Votre mot de passe a bien été modifié'
                );

                return $this->redirectToRoute('profil');


            }


            

        }
        else 
        {

            $this->addFlash(
               'error',
               'veuillez remplir le formulaire'
            );
        }

        return $this->render("user/password_modification.html.twig", [
            "formPassword" => $form->createView()
        ]);
    }














}
