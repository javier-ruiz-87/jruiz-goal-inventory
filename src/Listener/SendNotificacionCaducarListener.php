<?php
/**
 *  *
 * Created by PhpStorm.
 * User: Javier Ruiz
 * Date: 19/11/18
 * Time: 13:38
 */

namespace App\Listener;

use App\Entity\Articulo;
use App\Entity\Notificacion;
use App\Event\ArticuloCaducarEvent;
use App\Repository\NotificacionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class SendNotificacionCaducarListener
 */
class SendNotificacionCaducarListener implements EventSubscriberInterface
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
            ArticuloCaducarEvent::NAME => 'onCaducar',
        );
    }

    public function onCaducar(ArticuloCaducarEvent $event)
    {
        $articulo = $event->getArticulo();

        if ($articulo->getCaducado()) {
            $this->sendNotificacion($articulo);
        }
    }

    /**
     * Envio de notificacion puede ser envio de correo, SMS, etc.
     * En este caso a la consola y a la tabla de notificaciones
     *
     * @param Articulo $articulo
     *
     * @throws
     */
    public function sendNotificacion(Articulo $articulo)
    {
        $notificacion = new Notificacion();
        $notificacion->setMensaje('NOTIFICACION ARTICULO CADUCADO');
        $notificacion->setEntidad('Articulo');
        $notificacion->setFecha(new \DateTime('now'));
        $notificacion->setObjetoId($articulo->getId());
        $notificacion->setObjetoNombre($articulo->getNombre());

        /** @var NotificacionRepository $repositoryNotificaciones */
        $repositoryNotificaciones = $this->em->getRepository(Notificacion::class);
        $repositoryNotificaciones->save($notificacion);

        //Consola
        var_dump('NOTIFICACION ARTICULO CADUCADO: '.$articulo->getNombre().' ID: '.$articulo->getId());
    }

}
{

}