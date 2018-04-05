<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\MessageBundle\Model\ParticipantInterface;
/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity
 */
class Utilisateur extends BaseUser implements ParticipantInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=30, nullable=false)
     */
    protected $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="addresse", type="string", length=60, nullable=false)
     */
    protected $addresse;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer", nullable=false)
     */
    protected $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="text", length=65535, nullable=false)
     */
    protected $avatar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateInscription", type="date", nullable=true)
     */
    protected $dateinscription;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=30, nullable=false)
     */
    protected $role;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=20, nullable=true)
     */
    protected $sexe;

    /**
     * @var string
     *
     * @ORM\Column(name="confirmed", type="string", length=4, nullable=true)
     */
    protected $confirmed;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="text", length=65535, nullable=true)
     */
    protected $code;

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getAddresse()
    {
        return $this->addresse;
    }

    /**
     * @param string $addresse
     */
    public function setAddresse($addresse)
    {
        $this->addresse = $addresse;
    }

    /**
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param int $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return \DateTime
     */
    public function getDateinscription()
    {
        return $this->dateinscription;
    }

    /**
     * @param \DateTime $dateinscription
     */
    public function setDateinscription($dateinscription)
    {
        $this->dateinscription = $dateinscription;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * @param string $sexe
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
    }

    /**
     * @return string
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param string $confirmed
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */



}

