<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ligneachat
 *
 * @ORM\Table(name="ligneachat", indexes={@ORM\Index(name="id_produit", columns={"id_produit"}), @ORM\Index(name="id_achat", columns={"id_achat"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LigneachatRepository")
 */
class Ligneachat
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_ligne", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLigne;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbr_produit", type="integer", nullable=false)
     */
    private $nbrProduit;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_produit", referencedColumnName="id")
     * })
     */
    private $idProduit;

    /**
     * @var \Achat
     *
     * @ORM\ManyToOne(targetEntity="Achat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_achat", referencedColumnName="id_achat")
     * })
     */
    private $idAchat;

    /**
     * @return int
     */
    public function getIdLigne()
    {
        return $this->idLigne;
    }

    /**
     * @param int $idLigne
     */
    public function setIdLigne($idLigne)
    {
        $this->idLigne = $idLigne;
    }

    /**
     * @return int
     */
    public function getNbrProduit()
    {
        return $this->nbrProduit;
    }

    /**
     * @param int $nbrProduit
     */
    public function setNbrProduit($nbrProduit)
    {
        $this->nbrProduit = $nbrProduit;
    }

    /**
     * @return \Produit
     */
    public function getIdProduit()
    {
        return $this->idProduit;
    }

    /**
     * @param \Produit $idProduit
     */
    public function setIdProduit($idProduit)
    {
        $this->idProduit = $idProduit;
    }

    /**
     * @return \Achat
     */
    public function getIdAchat()
    {
        return $this->idAchat;
    }

    /**
     * @param \Achat $idAchat
     */
    public function setIdAchat($idAchat)
    {
        $this->idAchat = $idAchat;
    }


}

