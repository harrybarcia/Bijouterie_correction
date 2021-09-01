<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 * fields={"email"},
 * message="Cet email est déjà associé à un compte"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir votre email")
     */
    private $email;

    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

// ------------------------------------
    // /**
    //  * @ORM\Column(type="string", length=255)
    //  * @Assert\NotBlank(message="Veuillez saisir votre mot de passe")
    //  * @Assert\EqualTo(
    //  * propertyPath="confirmPassword",
    //  * message="Les mots de passe ne sont pas identiques"
    //  * )
    //  * 
    //  */
    // private $password;


    // /**
    //  * @Assert\NotBlank(message="Veuillez confirmer votre email")
    //  */
    // public $confirmPassword;
// ------------------------------------

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir votre nom")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir votre prénom")
     */
    private $prenom;

    
    /**
     * @ORM\Column(type="json")
     */
    private $roles = ['ROLE_USER'];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    /*
        L'entity User est différente des autres entity, dans le sens que c'est par cette entity qu'un utilisateur va pouvoir s'authentifier
        Symfony a déjà créé toute la sécurité
        il demande d'implémenter la class UserInterface 
        il faut rajouter des méthodes :
    */

    // Identitication 
    public function getUsername()
    {
        return $this->email;
    }

    public function getUserIdentifier()
    {
        return $this->email;
    }


    // Roles

    public function getRoles()
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }


    // renvoie la string (password) non encodé que l'utilisateur a saisi
    public function getSalt(){}

    // nettoie le mdp
    public function eraseCredentials(){}

}
