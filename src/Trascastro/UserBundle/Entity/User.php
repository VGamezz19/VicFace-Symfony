<?php
/**
 * (c) Ismael Trascastro <i.trascastro@gmail.com>
 *
 * @link        http://www.ismaeltrascastro.com
 * @copyright   Copyright (c) Ismael Trascastro. (http://www.ismaeltrascastro.com)
 * @license     MIT License - http://en.wikipedia.org/wiki/MIT_License
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trascastro\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="app_user")
 * @ORM\Entity(repositoryClass="Trascastro\UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{


    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Articulo", mappedBy="user")
     */
    private $articulos;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comentario", mappedBy="owner")
     */
    private $comentario;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Notificacion", mappedBy="ownerNotificacion")
     */
    private $notificacionOwner;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Notificacion", mappedBy="notificacionLlegadaUsuario")
     */
    private $notificacionLlegada;



    public function __construct()
    {
        parent::__construct();

        $this->createdAt    = new \DateTime();
        $this->updatedAt    = $this->createdAt;
    }


    //___________________________________________________________________________________________

    /**
     * @return mixed
     */
    public function getNotificacionLlegada()
    {
        return $this->notificacionLlegada;
    }

    /**
     * @param mixed $notificacionLlegada
     */
    public function setNotificacionLlegada($notificacionLlegada)
    {
        $this->notificacionLlegada = $notificacionLlegada;
    }

    /**
     * @return mixed
     */
    public function getNotificacionOwner()
    {
        return $this->notificacionOwner;
    }

    /**
     * @param mixed $notificacion
     */
    public function setNotificacionOwner($notificacionOwner)
    {
        $this->notificacionOwner = $notificacionOwner;
    }


    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
    }

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="upated_at", type="datetime")
     */
    private $updatedAt;


    public function setCreatedAt()
    {
        // never used
    }

    /**
     * @return mixed
     */
    public function getArticulos()
    {
        return $this->articulos;
    }

    /**
     * @param mixed $articulos
     */
    public function setArticulos($articulos)
    {
        $this->articulos = $articulos;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @ORM\PreUpdate()
     *
     * @return User
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function __toString()
    {
        return $this->username;
    }
}