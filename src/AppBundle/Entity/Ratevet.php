<?php
/**
 * Created by PhpStorm.
 * User: gmehd
 * Date: 04/05/2018
 * Time: 19:04
 */

namespace AppBundle\Entity;


class Ratevet
{
    public $id_vet;
    public $rate;
    public $nbr;

    /**
     * @return mixed
     */
    public function getNbr()
    {
        return $this->nbr;
    }

    /**
     * @param mixed $nbr
     */
    public function setNbr($nbr)
    {
        $this->nbr = $nbr;
    }

    /**
     * @return mixed
     */
    public function getIdVet()
    {
        return $this->id_vet;
    }

    /**
     * @param mixed $id_vet
     */
    public function setIdVet($id_vet)
    {
        $this->id_vet = $id_vet;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

}