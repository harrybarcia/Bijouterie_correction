<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findTout()
    {
        return $this->createQueryBuilder('p') // pas besoin de mettre from produit on est dans l'entité produit.
        // on mettra p l'alias
            ->getQuery()
            ->getResult()
            ;
    }

    public function findIdentifiant($id)
    {
        return $this->createQueryBuilder('p') // pas besoin de mettre from produit on est dans l'entité produit.
        // on mettra p l'alias
            ->andWhere('p.id=:id')
            ->setParameter("id", $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findPrix($prix)
    {
        return $this->createQueryBuilder('p') // pas besoin de mettre from produit on est dans l'entité produit.
        // on mettra p l'alias
            ->andWhere('p.prix=:marqueurPrix')
            ->setParameter("marqueurPrix", $prix)
            ->getQuery()
            ->getResult()
            ;
    }

    //.prix est la bdd
    //setparameter permet d'associer un marqueur à une valeur

    public function findOrderPrix()
    {
        return $this->createQueryBuilder('p')
            ->orderBy("p.prix", "DESC")
            ->setMaxResults(3) // LIMIT
            ->getQuery()
            ->getResult()
        ;

    }

    public function findCategorie($categorie)
    {
        return $this->createQueryBuilder('p')
            ->join('p.category', 'c')
            ->andWhere('c.id IN(:marqueurCategorie)')
            ->setParameter("marqueurCategorie", $categorie)
            ->getQuery()
            ->getResult()
        ;
    }
    /*
        rappel, la propriété catégorie dans l'entity Produit est la relation avec l'entity Category
        Si on veut sélectionner les produits en fonction de leur catégorie
        on doit créer une jointure
        on affecte à p.category un alias : c
        pour définir l'id ou le nom de la catégorie c.id c.nom
    */




    // p.prix est le champ prix de la table produit (p)
    // getparamater permet d'associer un marqueur à une valeur 

    // getResult() retourne un tableau
    // getOneOrNullResult() retourne UN SEUL objet

public function findBetween($p1, $p2){

    return $this->createQueryBuilder('p')
        ->andWhere('p.prix>:p1 AND p.prix<:p2')
        ->setParameter("p1", $p1)
        ->setParameter("p2", $p2)
        ->getQuery()
        ->getResult()
        ;


}

}