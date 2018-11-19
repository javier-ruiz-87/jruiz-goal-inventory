<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificacionRepository")
 */
class Notificacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mensaje;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $entidad;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $objetoNombre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $objetoId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(string $mensaje): self
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    public function getEntidad(): ?string
    {
        return $this->entidad;
    }

    public function setEntidad(?string $entidad): self
    {
        $this->entidad = $entidad;

        return $this;
    }

    public function getObjetoNombre(): ?string
    {
        return $this->objetoNombre;
    }

    public function setObjetoNombre(?string $objetoNombre): self
    {
        $this->objetoNombre = $objetoNombre;

        return $this;
    }

    public function getObjetoId(): ?int
    {
        return $this->objetoId;
    }

    public function setObjetoId(?int $objetoId): self
    {
        $this->objetoId = $objetoId;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(?\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }
}
