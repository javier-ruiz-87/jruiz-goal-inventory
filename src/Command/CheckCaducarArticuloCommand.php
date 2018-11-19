<?php
/**
 *  *
 * Created by PhpStorm.
 * User: Javier Ruiz
 * Date: 19/11/18
 * Time: 13:42
 */

namespace App\Command;

use App\Entity\Articulo;
use App\Manager\ArticuloManager;
use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CheckCaducarArticuloCommand
 */
class CheckCaducarArticuloCommand extends Command
{
    private $articuloManager;

    public function __construct(ArticuloManager $articuloManager)
    {
        $this->articuloManager = $articuloManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('goalinventory:caducar')
            ->setDescription('Notificar cuando un artículo caduca');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fechaActual = new DateTime();

        $output->writeln('----------------------');
        $output->writeln('Comprobamos los artículos del inventario caducado. Fecha actual: '.$fechaActual->format('d/m/Y'));

        $articulosCaducados = $this->articuloManager->buscarCaducados();

        if (!empty($articulosCaducados)) {
            foreach ($articulosCaducados as $el) {
                /** @var Articulo $articuloCaducado */
                $articuloCaducado = $el;

                //si no está ya caducado se caduca
                if(!$articuloCaducado->getCaducado()) {
                    $this->articuloManager->caducarArticulo($el);
                    $output->writeln(
                        '[ Fecha caducidad: '.$el->getFechaCaducidad()->format(
                            'd/m/Y'
                        ).' ]: Se ha caducado el artículo con ID: '.$articuloCaducado->getId()
                    );
                } else {
                    $output->writeln(
                        '[ Fecha caducidad: '.$articuloCaducado->getFechaCaducidad()->format(
                            'd/m/Y'
                        ).' ]: Articulo caducado con anterioridad con ID: '.$articuloCaducado->getId()
                    );
                }

            }

        } else {
            $output->writeln('No existen artículos que caducar. Artículos actualizados.');
        }

        $output->writeln('Fin comando');
        $output->writeln('----------------------');

        return null;

    }

}