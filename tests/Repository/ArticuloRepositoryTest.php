<?php
/**
 *  *
 * Created by PhpStorm.
 * User: Javier Ruiz
 * Date: 19/11/18
 * Time: 14:44
 */

namespace tests\Repository;

use App\Entity\Articulo;
use App\Repository\ArticuloRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ArticuloRepositoryTest
 */
class ArticuloRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testSearchByName()
    {

        $date = new \DateTime('now');
        $dateString = $date->getTimestamp();

        $nombre = 'trucha'.$dateString;
        $tipo = 'pescados';

        $articulo = new Articulo();
        $articulo->setNombre($nombre);
        $articulo->setTipo($tipo);
        $articulo->setDisponible(true);
        $articulo->setFechaCaducidad($date);
        $articulo->setCaducado(false);

        /** @var ArticuloRepository $repository */
        $repository = $this->entityManager->getRepository(Articulo::class);
        $repository->save($articulo);

        $articulo = $repository->findByNombre('trucha'.$dateString);

        $this->assertInstanceOf(Articulo::class, $articulo);
    }
}