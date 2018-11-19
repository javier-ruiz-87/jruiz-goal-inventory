<?php
/**
 *  *
 * Created by PhpStorm.
 * User: Javier Ruiz
 * Date: 19/11/18
 * Time: 13:01
 */
namespace App\Repository;

use App\Entity\Notificacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Notificacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notificacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notificacion[]    findAll()
 * @method Notificacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Notificacion::class);
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
