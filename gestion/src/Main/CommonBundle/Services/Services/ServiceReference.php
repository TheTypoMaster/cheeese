<?php

namespace Main\CommonBundle\Services\Services;

/**
 * Service de génération de reference aléatoires uniques.
 */
class ServiceReference
{
    private $alphabet = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
    private $tailleAlphabet = null;
    private $groupes = 4;
    private $tailleGroupe = 5;
    private $taille = 20;
    
    /**
     * Constructeur
     */
    function __construct()
    {
        $this->tailleAlphabet = strlen ( $this->alphabet );
    }
    
    /**
     * Retourne un numéro de référence unique généré aléatoirement
     * @return string
     */
    function generateReference()
    {
        $reference = '';
        $date      = new \DateTime();
        $reference .= $date->format('dmy').'-';
        for($g = 0; $g < $this->groupes; $g ++) {
            for($i = 0; $i < $this->tailleGroupe; $i ++) {
                $reference .= $this->alphabet [mt_rand ( 0, $this->tailleAlphabet - 1 )];
            }
            if ($g < $this->groupes - 1) {
                $reference .= '-';
            }
        }
        
        return $reference;
    }
}
