<?php
/**
 *  *
 * Created by PhpStorm.
 * User: Javier Ruiz
 * Date: 19/11/18
 * Time: 13:47
 */

namespace App\Entity;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class ArticuloTest
 */
class ArticuloTest extends TestCase
{
    public function testConstructArticulo()
    {
        $nombre = 'pizza';
        $tipo = 'congelados';
        $fechaCaducidad = new \DateTime('now');

        $articulo = new Articulo();
        $articulo->setNombre($nombre);
        $articulo->setTipo($tipo);
        $articulo->setFechaCaducidad($fechaCaducidad);

        $this->assertInstanceOf(Articulo::class, $articulo);
    }

}