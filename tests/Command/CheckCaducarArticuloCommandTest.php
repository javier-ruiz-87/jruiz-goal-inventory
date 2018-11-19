<?php
/**
 * Created by PhpStorm.
 * User: drh
 * Date: 19/11/18
 * Time: 19:16
 */

namespace tests\Command;

use App\Entity\Articulo;
use App\Entity\Notificacion;
use App\Manager\ArticuloManager;
use App\Repository\ArticuloRepository;
use App\Repository\NotificacionRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CheckCaducarArticuloCommand
 */
class CheckCaducarArticuloCommandTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function testCaducarArticulo()
    {
        $container = self::$kernel->getContainer();

        $date = new \DateTime('01-01-2018');
        $dateNow = new \DateTime('now');
        $dateString = $dateNow->getTimestamp();
        $nombre = 'salmon'.$dateString;
        $tipo = 'carnes';

        $articulo = new Articulo();
        $articulo->setNombre($nombre);
        $articulo->setTipo($tipo);
        $articulo->setFechaCaducidad($date);
        $articulo->setDisponible(true);
        $articulo->setCaducado(false);

        /** @var ArticuloRepository $articuloRepository */
        $articuloRepository = $this->entityManager->getRepository(Articulo::class);

        $articuloRepository->save($articulo, false);

        /** @var ArticuloManager $articuloManager */
        $articuloManager = $container->get('articulo.manager');
        $articuloManager->caducarArticulo($articulo);

        //se ha lanzado una notificacion
        /** @var NotificacionRepository $notifRepository */
        $notifRepository = $this->entityManager->getRepository(Notificacion::class);
        $notificacion = $notifRepository->findByObjetoNombre($articulo->getNombre());

        $this->assertInstanceOf(Notificacion::class, $notificacion);
    }

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

}