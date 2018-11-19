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
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

        $this->entityManager
            ->getRepository(Articulo::class)
            ->save($articulo)
        ;

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

        $this->nombre = $nombre;

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

        $this->entityManager
            ->getRepository(Articulo::class)
            ->save($articulo)
        ;

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
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/articulo-extract',
            array(
                'nombre' => 'burro',
            )
        );

        //espera exception
        $this->expectException('App\Exceptions\NoExisteArticuloexception');
//        $this->assertEquals(500, $client->getResponse()->getStatusCode());
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