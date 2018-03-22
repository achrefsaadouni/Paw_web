<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Messagerie
 *
 * @ORM\Table(name="messagerie")
 * @ORM\Entity
 */
class Messagerie
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
     * @var string
     *
     * @ORM\Column(name="ContenuMsg", type="text", length=65535, nullable=true)
     */
    private $contenumsg;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateHeureEnvoi", type="datetime", nullable=true)
     */
    private $dateheureenvoi;

    /**
     * @var integer
     *
     * @ORM\Column(name="sender_id", type="integer", nullable=true)
     */
    private $senderId;

    /**
     * @var integer
     *
     * @ORM\Column(name="reciever_id", type="integer", nullable=true)
     */
    private $recieverId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deletedSender", type="boolean", nullable=true)
     */
    private $deletedsender;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deletedReciever", type="boolean", nullable=true)
     */
    private $deletedreciever;


}

