<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notificacion
 *
 * @ORM\Table(name="notificacion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificacionRepository")
 */
class Notificacion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createAt", type="datetime")
     */
    private $createAt;

    /**
     * @ORM\ManyToOne(targetEntity="Trascastro\UserBundle\Entity\User", inversedBy="notificacionOwner")
     */
    private $ownerNotificacion;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Articulo", inversedBy="notificacionArticulo")
     */
    private $articuloNotificacion;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Comentario", inversedBy="notificacionComentario")
     */
    private $comentarioNotificacion;

    /**
     * @ORM\ManyToOne(targetEntity="Trascastro\UserBundle\Entity\User", inversedBy="notificacionLlegada")
     */
    private $notificacionLlegadaUsuario;


    //_____________________________________________________________

    /**
     * Notificacion constructor
     * @param \DateTime $createAt
     * @param \int $count
     */
    public function __construct()
    {
        $this->createAt = new \DateTime();
        $this->count = +1; //Añadimos valor a la notificacion al crearla.
    }

    /**
     * @return mixed
     */
    public function getNotificacionLlegadaUsuario()
    {
        return $this->notificacionLlegadaUsuario;
    }

    /**
     * @param mixed $notificacionLlegadaUsuario
     */
    public function setNotificacionLlegadaUsuario($notificacionLlegadaUsuario)
    {
        $this->notificacionLlegadaUsuario = $notificacionLlegadaUsuario;
    }



    /**
     * @return mixed
     */
    public function getArticuloNotificacion()
    {
        return $this->articuloNotificacion;
    }

    /**
     * @param mixed $articuloNotificacion
     */
    public function setArticuloNotificacion($articuloNotificacion)
    {
        $this->articuloNotificacion = $articuloNotificacion;
    }

    /**
     * @return mixed
     */
    public function getComentarioNotificacion()
    {
        return $this->comentarioNotificacion;
    }

    /**
     * @param mixed $comentarioNotificacion
     */
    public function setComentarioNotificacion($comentarioNotificacion)
    {
        $this->comentarioNotificacion = $comentarioNotificacion;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getOwnerNotificacion()
    {
        return $this->ownerNotificacion;
    }

    /**
     * @param mixed $ownerNotificacion
     */
    public function setOwnerNotificacion($ownerNotificacion)
    {
        $this->ownerNotificacion = $ownerNotificacion;
    }



    /**
     * Set count
     *
     * @param integer $count
     *
     * @return Notificacion
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Notificacion
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }
}

