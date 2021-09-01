<?php 


namespace App\Service;

use App\Entity\Produit;



class DateFr 
{

    public function moisArrayFr()
    {
        $moisArrayFr = [
            "janvier",
            "février",
            "mars",
            "avril",
            "mai",
            "juin",
            "juillet",
            "août",
            "septembre",
            "octobre",
            "novembre",
            "décembre"
        ];
        return $moisArrayFr;
    }

    public function moisFr1($numMoisArgument)
    {
        $moisArrayFr=$this->moisArrayFr();

        foreach($moisArrayFr as $key => $value)
        {
            if($key +1  == $numMoisArgument)
            {
                $moisFr = $value;
            }
        }

        return $moisFr;




    }


    public function moisFr2($date_objet_arg)
    {
        $jour=$date_objet_arg->format("d");
        $mois=$date_objet_arg->format("m");
        $annee=$date_objet_arg->format("Y");

        $moisFr=$this->moisFr1($mois);

        $dateEntiere="$jour $moisFr $annee";

        return $dateEntiere;
    }

    public function moisFr3($produit_objet_argument)
    {
        //receptionne la date de mon objet
        $dateObjet=$produit_objet_argument->getDateAt();

        // j'applique la méthode fr2 à cette date
        $dateEntiere=$this->moisFr2($dateObjet);

        // je change la valeur de ma propriété
        $produit_objet_argument->newDate=$dateEntiere;

        // Je la retourne
        return $produit_objet_argument;
    }



}