<?php
/**
 * Created by PhpStorm.
 * User: Guideinfo
 * Date: 09/04/2018
 * Time: 18:24
 */

namespace AppBundle\Repository;


class AnnonceRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAnnonce($search)
    {
        return $this->createQueryBuilder('e')
            ->where('UPPER(e.lieuPerdu) LIKE UPPER(:search)')
            ->setParameter('search', '%'.$search.'%')
            ->orderBy('e.datePerte', 'DESC')
            ->getQuery()
            ->getResult();

    }

    public function findAnnonce2($search)
    {
        return $this->createQueryBuilder('e')
            ->where('UPPER(e.lieuTrouve) LIKE UPPER(:search)')
            ->setParameter('search', '%'.$search.'%')
            ->orderBy('e.dateTrouvee', 'DESC')
            ->getQuery()
            ->getResult();

    }
    public function findRech($search)
    {
        return $this->createQueryBuilder('e')
            ->where('UPPER(e.race) LIKE UPPER(:search)')
            ->orWhere('UPPER(e.type) LIKE UPPER(:search)')
            ->orWhere('UPPER(e.messageComplementaire) LIKE UPPER(:search)')
            ->orWhere('UPPER(e.couleur) LIKE UPPER(:search)')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery()
            ->getResult();

    }
}