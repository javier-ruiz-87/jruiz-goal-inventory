<?php
/**
 *  *
 * Created by PhpStorm.
 * User: Javier Ruiz
 * Date: 19/11/18
 * Time: 13:40
 */

namespace App\Listener;

use App\Entity\Articulo;
use App\Entity\Notificacion;
use App\Event\ArticuloNoDisponibleEvent;
use App\Repository\NotificacionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class SendNotificacionNoDisponibleListener
 */
class SendNotificacionNoDisponibleListener implements EventSubscriberInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            ArticuloNoDisponibleEvent::NAME => 'onNoDisponible',
        );
    }

    public function onNoDisponible(ArticuloNoDisponibleEvent $event)
    {
        $articulo = $event->getArticulo();

        if (!$articulo->getDisponible()) {
            $this->sendNotificacion($articulo);
        }
    }

    /**
     * Envio de notificación puede ser envio de correo, SMS, etc.
     * En este caso a una tabla de notificaciones
     *
     * @param Articulo $articulo
     *
     * @throws
     */
    public function sendNotificacion(Articulo $articulo)
    {
        $notificacion = new Notificacion();
        $notificacion->setMensaje('NOTIFICACION ARTICULO NO DISPONIBLE');
        $notificacion->setEntidad('Articulo');
        $notificacion->setFecha(new \DateTime('now'));
        $notificacion->setObjetoId($articulo->getId());
        $notificacion->setObjetoNombre($articulo->getNombre());

        /** @var NotificacionRepository $repositoryNotificaciones */
        $repositoryNotificaciones = $this->em->getRepository(Notificacion::class);
        $repositoryNotificaciones->save($notificacion);
    }
}