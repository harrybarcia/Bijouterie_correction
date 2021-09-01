<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/inscription', name: 'inscription')]
    public function inscription(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder)
    {
        $user=new User;
        $form=$this->createForm(UserType::class,$user,['inscription'=>true]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

        $hash=$encoder->hashPassword($user, $user->getPassword());
        dump($hash);


            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Votre inscription a bien été enregistrée, vous pouvez vous connecter");

            return $this->redirectToRoute("connexion");
        }
        return $this->render('security/inscription.html.twig',[
            "formUser"=>$form->createView()
        ]);
    }

    #[Route('/connexion', name: 'connexion')]
    public function connexion(){
        return $this->render("security/connexion.html.twig");
    }

    #[Route('/deconnexion', name: 'deconnexion')]
    public function deconnexion(){}

    // lorsqu'un utilisateur s'authentifie, il est redirigé sur la route role qui permet de checker son role.
    #[Route('/roles', name: 'roles')]
    public function roles(){
        if($this->isGranted('ROLE_ADMIN')) //si la personne connectée est admin
        {
            return $this->redirectToRoute("back_office");
        }
        elseif($this->isGranted('ROLE_USER')) //si la personne connectée est admin
        {
            return $this->redirectToRoute("profil");
        }

    }
}

