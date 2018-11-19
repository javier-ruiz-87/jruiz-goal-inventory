<?php
/**
 *  *
 * Created by PhpStorm.
 * User: Javier Ruiz
 * Date: 19/11/18
 * Time: 13:45
 */

namespace tests\Controller;

use App\Entity\Articulo;
use App\Exceptions\NoExisteArticuloException;
use App\Repository\ArticuloRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class APIArticuloControllerTest
 */
class APIArticuloControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function testPostArticuloExists()
    {
        $date = new \DateTime('now');
        $dateString = $date->getTimestamp();
        $nombre = 'pollo'.$dateString;
        $tipo = 'congelados';

        $articulo = new Articulo();
        $articulo->setNombre($nombre);
        $articulo->setTipo($tipo);
        $articulo->setFechaCaducidad($date);
        $articulo->setDisponible(true);
        $articulo->setCaducado(false);

        /** @var ArticuloRepository $articuloRepository */
        $articuloRepository = $this->entityManager->getRepository(Articulo::class);
        $articuloRepository->save($articulo);

        $client = static::createClient();

        $client->request(
            'POST',
            '/api/articulo-nuevo',
            array(
                'nombre' => $nombre,
                'tipo'   => 'carnes'
            )
        );

        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }

    public function testPostArticuloNew()
    {
        $client = static::createClient();

        $date = new \DateTime('now');
        $dateString = $date->getTimestamp();

        $nombre = 'ternera'.$dateString;

        $client->request(
            'POST',
            '/api/articulo-nuevo',
            array(
                'nombre' => $nombre,
                'tipo'   => 'carnes'
            )
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testExtractArticuloExists()
    {
        $date = new \DateTime('now');
        $dateString = $date->getTimestamp();
        $nombre = 'pizza'.$dateString;
        $tipo = 'congelados';

        $articulo = new Articulo();
        $articulo->setNombre($nombre);
        $articulo->setTipo($tipo);
        $articulo->setFechaCaducidad($date);
        $articulo->setDisponible(true);
        $articulo->setCaducado(false);

        /** @var ArticuloRepository $articuloRepository */
        $articuloRepository = $this->entityManager->getRepository(Articulo::class);
        $articuloRepository->save($articulo);

        $client = static::createClient();

        $client->request(
            'POST',
            '/api/articulo-extract',
            array(
                'nombre' => $nombre,
            )
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testExtractArticuloNotExists()
    {
        //espera exception
        $this->expectException(NoExisteArticuloexception::class);

        $client = static::createClient();

        $client->request(
            'POST',
            '/api/articulo-extract',
            array(
                'nombre' => 'burro',
            )
        );

        $this->assertEquals(500, $client->getResponse()->getStatusCode());
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