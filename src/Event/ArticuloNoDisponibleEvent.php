<?php
/**
 *  *
 * Created by PhpStorm.
 * User: Javier Ruiz
 * Date: 19/11/18
 * Time: 13:36
 */

namespace App\Event;

use App\Entity\Articulo;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ArticuloNoDisponibleEvent
 */
class ArticuloNoDisponibleEvent extends Event
{
    const NAME = 'articulo.no_disponible';

    /** @var Articulo $articulo */
    private $articulo;

    /**
     * ArticuloNoDisponibleEvent constructor.
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