<?php

/**
 *  *
 * Created by PhpStorm.
 * User: Javier Ruiz
 * Date: 19/11/18
 * Time: 13:22
 */
namespace App\Manager;

use App\Entity\Articulo;
use App\Event\ArticuloCaducarEvent;
use App\Event\ArticuloNoDisponibleEvent;
use App\Repository\ArticuloRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ArticuloManager
 */
class ArticuloManager
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var Articulo $entityClass */
    protected $entityClass;

    /** @var ArticuloRepository $entityRepository */
    protected $entityRepository;

    /** @var EventDispatcherInterface */
    private $dispatcher;

    /**
     * ArticuloManager constructor.
     *
     * @param EntityManagerInterface   $em
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->entityManager = $em;
        $this->dispatcher = $dispatcher;
        $this->entityRepository = $this->entityManager->getRepository(Articulo::class);
    }

    public function save(Articulo $articulo, $flush = true)
    {
        $this->entityManager->persist($articulo);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function getArticuloByNombre($nombre)
    {
        return $this->entityRepository->findByNombre($nombre);
    }

    public function noDisponibleArticulo(Articulo $articulo)
    {
        $articulo->setDisponible(false);
        $this->save($articulo);

        $noDisponibleEvento = new ArticuloNoDisponibleEvent($articulo);

        $this->dispatcher->dispatch(
            ArticuloNoDisponibleEvent::NAME,
            $noDisponibleEvento
        );
    }

    public function buscarCaducados()
    {
        return $this->entityRepository->findArticulosCaducados();
    }

    public function caducarArticulo(Articulo $articulo)
    {
        $articulo->setCaducado(true);

        $caducarEvento = new ArticuloCaducarEvent($articulo);

        $this->dispatcher->dispatch(
            ArticuloCaducarEvent::NAME,
            $caducarEvento
        );

        $this->save($articulo);
    }
}