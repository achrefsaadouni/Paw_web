<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Annonce
 *
 * @ORM\Table(name="annonce", indexes={@ORM\Index(name="utilisateur_id", columns={"utilisateur_id"})})
 * @ORM\Entity
 */
class Annonce
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="integer", nullable=false)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="couleur", type="string", length=50, nullable=false)
     */
    private $couleur;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=50, nullable=false)
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="race", type="string", length=50, nullable=false)
     */
    private $race;

    /**
     * @var string
     *
     * @ORM\Column(name="message_complementaire", type="text", length=65535, nullable=false)
     */
    private $messageComplementaire;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50, nullable=false)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="type_annonce", type="string", length=50, nullable=true)
     */
    private $typeAnnonce;

    /**
     * @var string
     *
     * @ORM\Column(name="colier", type="string", length=50, nullable=true)
     */
    private $colier;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_trouvee", type="date", nullable=true)
     */
    private $dateTrouvee;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu_trouve", type="string", length=50, nullable=true)
     */
    private $lieuTrouve;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_perte", type="datetime", nullable=true)
     */
    private $datePerte;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu_perdu", type="string", length=50, nullable=true)
     */
    private $lieuPerdu;

    /**
     * @var string
     *
     * @ORM\Column(name="type_poil", type="string", length=55, nullable=true)
     */
    private $typePoil;

    /**
     * @var string
     *
     * @ORM\Column(name="vaccin", type="string", length=20, nullable=true)
     */
    private $vaccin;

    /**
     * @var string
     *
     * @ORM\Column(name="dossier", type="string", length=20, nullable=true)
     */
    private $dossier;

    /**
     * @var string
     *
     * @ORM\Column(name="images", type="text", length=65535, nullable=true)
     */
    private $images;

    /**
     * @var string
     *
     * @ORM\Column(name="nomPet", type="string", length=30, nullable=true)
     */
    private $nompet;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateTr", type="date", nullable=true)
     */
    private $datetr;

    /**
     * @var string
     *
     * @ORM\Column(name="typeTr", type="string", length=60, nullable=true)
     */
    private $typetr;

    /**
     * @var string
     *
     * @ORM\Column(name="typeAdoption", type="string", length=30, nullable=true)
     */
    private $typeadoption;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="debutAdoption", type="date", nullable=true)
     */
    private $debutadoption;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finAdoption", type="date", nullable=true)
     */
    private $finadoption;

    /**
     * @var string
     *
     * @ORM\Column(name="etatAdoption", type="string", length=30, nullable=true)
     */
    private $etatadoption;

    /**
     * @var string
     *
     * @ORM\Column(name="todolist", type="text", length=65535, nullable=true)
     */
    private $todolist;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datesit", type="date", nullable=true)
     */
    private $datesit;

    /**
     * @var integer
     *
     * @ORM\Column(name="duresit", type="integer", nullable=true)
     */
    private $duresit;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     * })
     */
    private $utilisateur;


}

