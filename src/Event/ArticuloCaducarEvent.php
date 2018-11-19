<?php
/**
 *  *
 * Created by PhpStorm.
 * User: Javier Ruiz
 * Date: 19/11/18
 * Time: 13:35
 */

namespace App\Event;

use App\Entity\Articulo;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ArticuloCaducarEvent
 */
class ArticuloCaducarEvent extends Event
{
    const NAME = 'articulo.caducado';

    /** @var Articulo $articulo */
    private $articulo;

    /**
     * ArticuloCaducarEvent constructor.
     *
     * @param Articulo $articulo
     */
    public function __construct(Articulo $articulo)
    {
        $this->articulo = $articulo;
    }

    /**
     * @return Articulo
     */
    public function getArticulo(): Articulo
    {
        return $this->articulo;
    }

    /**
     * @param Articulo $articulo
     */
    public function setArticulo(Articulo $articulo): void
    {
        $this->articulo = $articulo;
    }
}