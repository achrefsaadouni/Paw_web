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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return string
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * @param string $couleur
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;
    }

    /**
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param string $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return string
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * @param string $race
     */
    public function setRace($race)
    {
        $this->race = $race;
    }
    /**
     * Constructor
     */
    public function __construct()
    {

        $this->date = new \DateTime();


    }
    /**
     * @return string
     */
    public function getMessageComplementaire()
    {
        return $this->messageComplementaire;
    }

    /**
     * @param string $messageComplementaire
     */
    public function setMessageComplementaire($messageComplementaire)
    {
        $this->messageComplementaire = $messageComplementaire;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getTypeAnnonce()
    {
        return $this->typeAnnonce;
    }

    /**
     * @param string $typeAnnonce
     */
    public function setTypeAnnonce($typeAnnonce)
    {
        $this->typeAnnonce = $typeAnnonce;
    }

    /**
     * @return string
     */
    public function getColier()
    {
        return $this->colier;
    }

    /**
     * @param string $colier
     */
    public function setColier($colier)
    {
        $this->colier = $colier;
    }

    /**
     * @return \DateTime
     */
    public function getDateTrouvee()
    {
        return $this->dateTrouvee;
    }

    /**
     * @param \DateTime $dateTrouvee
     */
    public function setDateTrouvee($dateTrouvee)
    {
        $this->dateTrouvee = $dateTrouvee;
    }

    /**
     * @return string
     */
    public function getLieuTrouve()
    {
        return $this->lieuTrouve;
    }

    /**
     * @param string $lieuTrouve
     */
    public function setLieuTrouve($lieuTrouve)
    {
        $this->lieuTrouve = $lieuTrouve;
    }

    /**
     * @return \DateTime
     */
    public function getDatePerte()
    {
        return $this->datePerte;
    }

    /**
     * @param \DateTime $datePerte
     */
    public function setDatePerte($datePerte)
    {
        $this->datePerte = $datePerte;
    }

    /**
     * @return string
     */
    public function getLieuPerdu()
    {
        return $this->lieuPerdu;
    }

    /**
     * @param string $lieuPerdu
     */
    public function setLieuPerdu($lieuPerdu)
    {
        $this->lieuPerdu = $lieuPerdu;
    }

    /**
     * @return string
     */
    public function getTypePoil()
    {
        return $this->typePoil;
    }

    /**
     * @param string $typePoil
     */
    public function setTypePoil($typePoil)
    {
        $this->typePoil = $typePoil;
    }

    /**
     * @return string
     */
    public function getVaccin()
    {
        return $this->vaccin;
    }

    /**
     * @param string $vaccin
     */
    public function setVaccin($vaccin)
    {
        $this->vaccin = $vaccin;
    }

    /**
     * @return string
     */
    public function getDossier()
    {
        return $this->dossier;
    }

    /**
     * @param string $dossier
     */
    public function setDossier($dossier)
    {
        $this->dossier = $dossier;
    }

    /**
     * @return string
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param string $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return string
     */
    public function getNompet()
    {
        return $this->nompet;
    }

    /**
     * @param string $nompet
     */
    public function setNompet($nompet)
    {
        $this->nompet = $nompet;
    }

    /**
     * @return \DateTime
     */
    public function getDatetr()
    {
        return $this->datetr;
    }

    /**
     * @param \DateTime $datetr
     */
    public function setDatetr($datetr)
    {
        $this->datetr = $datetr;
    }

    /**
     * @return string
     */
    public function getTypetr()
    {
        return $this->typetr;
    }

    /**
     * @param string $typetr
     */
    public function setTypetr($typetr)
    {
        $this->typetr = $typetr;
    }

    /**
     * @return string
     */
    public function getTypeadoption()
    {
        return $this->typeadoption;
    }

    /**
     * @param string $typeadoption
     */
    public function setTypeadoption($typeadoption)
    {
        $this->typeadoption = $typeadoption;
    }

    /**
     * @return \DateTime
     */
    public function getDebutadoption()
    {
        return $this->debutadoption;
    }

    /**
     * @param \DateTime $debutadoption
     */
    public function setDebutadoption($debutadoption)
    {
        $this->debutadoption = $debutadoption;
    }

    /**
     * @return \DateTime
     */
    public function getFinadoption()
    {
        return $this->finadoption;
    }

    /**
     * @param \DateTime $finadoption
     */
    public function setFinadoption($finadoption)
    {
        $this->finadoption = $finadoption;
    }

    /**
     * @return string
     */
    public function getEtatadoption()
    {
        return $this->etatadoption;
    }

    /**
     * @param string $etatadoption
     */
    public function setEtatadoption($etatadoption)
    {
        $this->etatadoption = $etatadoption;
    }

    /**
     * @return string
     */
    public function getTodolist()
    {
        return $this->todolist;
    }

    /**
     * @param string $todolist
     */
    public function setTodolist($todolist)
    {
        $this->todolist = $todolist;
    }

    /**
     * @return \DateTime
     */
    public function getDatesit()
    {
        return $this->datesit;
    }

    /**
     * @param \DateTime $datesit
     */
    public function setDatesit($datesit)
    {
        $this->datesit = $datesit;
    }

    /**
     * @return int
     */
    public function getDuresit()
    {
        return $this->duresit;
    }

    /**
     * @param int $duresit
     */
    public function setDuresit($duresit)
    {
        $this->duresit = $duresit;
    }

    /**
     * @return \Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param \Utilisateur $utilisateur
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

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

