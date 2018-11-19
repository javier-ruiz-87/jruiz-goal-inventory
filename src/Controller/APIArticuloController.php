<?php
/**
 *  *
 * Created by PhpStorm.
 * User: Javier Ruiz
 * Date: 19/11/18
 * Time: 13:15
 */
namespace App\Controller;

use App\Entity\Articulo;
use App\Exceptions\NoExisteArticuloException;
use App\Repository\ArticuloRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class APIArticuloController
 * @Route("/api")
 */
class APIArticuloController extends Controller
{
    /**
     * Crear articulo del inventario. Ejecutar con Postman
     * @Route("/articulo-nuevo", name="articulo_api_post", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postArticuloAction(Request $request)
    {
        try {
            $articulo = new Articulo();
            $articulo->setNombre($request->get('nombre'));
            $articulo->setTipo($request->get('tipo'));
            $date = new DateTime($request->get('fecha_caducidad'));
            $articulo->setFechaCaducidad($date);
            $articulo->setDisponible(true);
            $articulo->setCaducado(false);
            $this->get('articulo.manager')->save($articulo);
            $jsonMsg = $this->serialize(
                array(
                    'mensaje' => 'Exito al guardar',
                    'codigo'  => '1'
                ));
            $response = new Response($jsonMsg, 201);
        } catch (\Exception $e) {
            $jsonMsg = $this->serialize(
                array(
                    'mensaje' => $e->getMessage(),
                    'code'    => $e->getCode(),
                    'codigo'  => '0'
                ));
            $response = new Response($jsonMsg, 500);
        }

        return $response;
    }


    /**
     * @Route("/articulo-extract", name="articulo_api_extract", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     * @throws NoExisteArticuloException
     */
    public function extractArticuloAction(Request $request)
    {
        $nombre = $request->get('nombre');
        /** @var Articulo $articulo */
        $articulo = $this->get('articulo.manager')->getArticuloByNombre($nombre);

        if (!$articulo) {
            throw new NoExisteArticuloException('No se ha encontrado un articulo con nombre: '.$nombre);
        }

        if($articulo->getDisponible()) {
            $this->get('articulo.manager')->noDisponibleArticulo($articulo);

            $jsonArticulo = $this->serialize(
                array(
                    'item' => $articulo,
                    'msg' => 'El articulo con nombre '.$articulo->getNombre().' ha dejado de estar disponible.'
                )
            );
        } else {
            throw new NoExisteArticuloException('No esta disponible para extraer el articulo con nombre: '.$nombre);
        }

        $response = new Response($jsonArticulo,200);

        return $response;
    }

    /**
     * Lists all Articles.
     * @Route("/articulos-list", name="articulos_api_list", methods={"GET"})
     *
     * @return Response
     */
    public function getArticulosAction()
    {
        /** @var ArticuloRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Articulo::class);

        $articulos = $repository->findArticulosDisponibles();

        $jsonArticulos = $this->serialize(
            array(
                'items' => $articulos,
                'totalItems'=> count($articulos)
            )
        );

        $response = new Response( $jsonArticulos,200);

        return $response;
    }

    /**
     * Serializer some date to json
     *
     * @param $data
     *
     * @return mixed
     */
    private function serialize($data)
    {
        return $this->container->get('serializer')
            ->serialize($data, 'json');
    }
}
