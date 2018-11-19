<?php
/**
 *  *
 * Created by PhpStorm.
 * User: Javier Ruiz
 * Date: 19/11/18
 * Time: 13:00
 */
namespace App\Repository;

use App\Entity\Articulo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Articulo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articulo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articulo[]    findAll()
 * @method Articulo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticuloRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Articulo::class);
    }

    public function findByNombre($nombre)
    {
        return $this->findOneBy(array('nombre' => $nombre));
    }

    public function findArticulosDisponibles($disponible = true)
    {
        return $this->createQueryBuilder('p')
            ->where('p.disponible = :val')
            ->setParameter('val', $disponible)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Buscamos artículos caducados por fecha
     *
     * @return array
     */
    public function findArticulosCaducados()
    {
        return $this->createQueryBuilder('p')
            ->where('p.fechaCaducidad <= :val')
            ->setParameter('val', new \DateTime('now'))
            ->getQuery()
            ->getResult()
            ;
    }

    public function noDisponibleArticulo(Articulo $articulo)
    {
        $articulo->setDisponible(false);
        $this->save($articulo);

        return $articulo;
    }

    /**
     * @param Articulo $articulo
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function caducarArticulo(Articulo $articulo)
    {
        $articulo->setCaducado(true);
        $this->save($articulo);
    }

    /**
     * @param $data
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($data)
    {
        $this->_em->persist($data);

        $this->_em->flush();
    }
}
